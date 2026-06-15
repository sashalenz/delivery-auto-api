<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto;

use Carbon\Carbon;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Sashalenz\DeliveryAuto\Exceptions\DeliveryAutoApiUnavailableException;
use Sashalenz\DeliveryAuto\Exceptions\DeliveryAutoException;

final class Request
{
    private const TIMEOUT = 10;

    private const RETRY_TIMES = 3;

    private const RETRY_SLEEP = 100;

    public const AUTH_NONE = 'none';

    public const AUTH_HMAC = 'hmac';

    public const AUTH_SESSION = 'session';

    /** Marker for endpoints whose payload sits at the response root, not under `data`. */
    public const DATA_KEY_ROOT = '__root__';

    public function __construct(
        private readonly string $method,
        private readonly array $params,
        private readonly bool $isPost = false,
        private readonly string $authMode = self::AUTH_NONE,
        private readonly string $dataKey = 'data',
        private readonly ?string $publicKey = null,
        private readonly ?string $secretKey = null,
    ) {}

    /**
     * @return Collection<array-key, mixed>
     *
     * @throws DeliveryAutoException
     */
    public function make(): Collection
    {
        try {
            $response = $this->prepareRequest()
                ->when(
                    $this->isPost,
                    fn (PendingRequest $r) => $r->post($this->method, $this->params),
                    fn (PendingRequest $r) => $r->get($this->method, $this->params)
                )
                ->throw();
        } catch (ConnectionException $e) {
            throw new DeliveryAutoApiUnavailableException(
                'Delivery-Auto API unreachable. '.$e->getMessage(),
                previous: $e
            );
        } catch (RequestException $e) {
            if ($e->response->serverError()) {
                throw new DeliveryAutoApiUnavailableException(
                    'Delivery-Auto API server error. '.$e->getMessage(),
                    previous: $e
                );
            }

            throw new DeliveryAutoException(
                'Delivery-Auto API request error. '.$e->getMessage(),
                previous: $e
            );
        }

        $payload = self::decodeBody($response);

        if ($payload->get('status') !== true) {
            $code = (int) $payload->get('code', 0);

            throw new DeliveryAutoException(
                'Delivery-Auto API error. '.self::formatErrorMessage($payload->get('message')),
                $code
            );
        }

        if ($this->authMode === self::AUTH_SESSION) {
            $this->captureSessionCookies($response);
        }

        if ($this->dataKey === self::DATA_KEY_ROOT) {
            // Strip envelope keys; return everything else as-is.
            return $payload->except(['status', 'message', 'code']);
        }

        $value = $payload->get($this->dataKey);

        if ($value === null) {
            return collect();
        }

        if (is_array($value)) {
            return collect($value);
        }

        return collect([$value]);
    }

    /**
     * Decode the JSON envelope, normalising the wire encoding to UTF-8 first.
     *
     * Delivery-Auto answers some endpoints — notably the receipt-validation
     * errors of PostCreateReceipts — in Windows-1251. PHP's json_decode only
     * accepts UTF-8 and would reject those bytes outright, so the raw body is
     * transcoded whenever it isn't already valid UTF-8 before parsing. UTF-8
     * responses (every success payload) pass through the check untouched.
     *
     * @return Collection<array-key, mixed>
     */
    private static function decodeBody(Response $response): Collection
    {
        $body = $response->body();

        if (! mb_check_encoding($body, 'UTF-8')) {
            $body = mb_convert_encoding($body, 'UTF-8', 'Windows-1251');
        }

        $decoded = json_decode($body, true);

        return collect(is_array($decoded) ? $decoded : []);
    }

    /**
     * Flatten the `message` envelope field into one operator-readable string.
     *
     * Delivery-Auto returns validation errors for some endpoints (notably
     * PostCreateReceipts) as an ARRAY of messages — one entry per failed
     * field — rather than a single string. json_encode-ing that array leaked
     * a `["..."]` blob to the operator; instead each error is rendered on its
     * own line. Wire-level Windows-1251 is already normalised to UTF-8 in
     * {@see decodeBody}.
     */
    private static function formatErrorMessage(mixed $message): string
    {
        return collect(is_array($message) ? $message : [$message])
            ->filter(fn ($line): bool => is_string($line) && $line !== '')
            ->implode(PHP_EOL);
    }

    /**
     * @return Collection<array-key, mixed>
     *
     * @throws DeliveryAutoException
     */
    public function cache(int $seconds = -1): Collection
    {
        if ($seconds === -1) {
            return Cache::rememberForever($this->getCacheKey(), fn () => $this->make());
        }

        return Cache::remember($this->getCacheKey(), $seconds, fn () => $this->make());
    }

    private function prepareRequest(): PendingRequest
    {
        $request = Http::timeout(self::TIMEOUT)
            ->retry(self::RETRY_TIMES, self::RETRY_SLEEP, throw: false)
            ->baseUrl((string) config('delivery-auto-api.url'))
            ->asJson();

        if ($this->authMode === self::AUTH_HMAC) {
            $request = $request->withHeaders([
                'HMACAuthorization' => 'amx '.$this->hmacHeader(),
            ]);
        }

        if ($this->authMode === self::AUTH_SESSION) {
            $jar = SessionStore::cookies();
            if ($jar instanceof CookieJar) {
                $request = $request->withOptions(['cookies' => $jar]);
            } else {
                // First request in the session (login) — provide an empty jar so
                // Set-Cookie headers from the response can be captured.
                $request = $request->withOptions(['cookies' => new CookieJar]);
            }
        }

        return $request;
    }

    /**
     * Build the HMAC authorisation token: `publicKey:timestamp:hmac256(publicKey+timestamp, secretKey)`.
     *
     * Per PDF v3.5.1 §6.1 — algorithm is HMAC-SHA256 (NOT SHA1, despite the package's
     * pre-2.0 implementation). Timestamp is Unix epoch seconds.
     *
     * Per-call credentials override `config('delivery-auto-api.{public,secret}_key')`,
     * so multi-sender apps can route a single request to a specific company keypair.
     */
    private function hmacHeader(): string
    {
        $publicKey = $this->publicKey ?? (string) config('delivery-auto-api.public_key');
        $secretKey = $this->secretKey ?? (string) config('delivery-auto-api.secret_key');
        $timestamp = (string) Carbon::now()->timestamp;

        $hash = hash_hmac('sha256', $publicKey.$timestamp, $secretKey);

        return $publicKey.':'.$timestamp.':'.$hash;
    }

    private function captureSessionCookies(Response $response): void
    {
        SessionStore::store($response->cookies());
    }

    private function getCacheKey(): string
    {
        return collect([
            'delivery-auto',
            $this->method,
            $this->isPost ? 'post' : 'get',
            $this->authMode,
            // Include a public-key hash so multi-sender apps don't share cache
            // entries between different HMAC keypairs. Hash, not the raw key,
            // so cache stores never have credential material in their keys.
            $this->publicKey === null ? '_' : substr(sha1($this->publicKey), 0, 8),
            base64_encode(serialize($this->params)),
        ])->implode('_');
    }
}
