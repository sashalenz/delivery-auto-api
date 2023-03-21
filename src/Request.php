<?php

namespace Sashalenz\DeliveryAuto;

use Carbon\Carbon;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Sashalenz\DeliveryAuto\Exceptions\DeliveryException;

final class Request
{
    private const TIMEOUT = 10;
    private const RETRY_TIMES = 3;
    private const RETRY_SLEEP = 100;

    private string $url;
    private string $method;
    private array $params;
    private bool $auth;
    private string $dataKey;
    private bool $isPost;

    public function __construct(
        string $method,
        array $params,
        bool $auth,
        string $dataKey,
        bool $isPost = false
    ) {
        $this->method = $method;
        $this->params = $params;
        $this->auth = $auth;
        $this->dataKey = $dataKey;
        $this->isPost = $isPost;

        $this->url = Config::get('delivery-api.url');
    }

    /**
     * @return Collection
     * @throws DeliveryException
     */
    public function make(): Collection
    {
        try {
            $headers = [];

            if ($this->auth) {
                $headers['HMACAuthorization'] = 'amx ' . $this->hash();
            }

            $request = Http::timeout(self::TIMEOUT)
                ->retry(
                    self::RETRY_TIMES,
                    self::RETRY_SLEEP
                )
                ->baseUrl($this->url)
                ->asJson()
                ->withHeaders($headers);

            if ($this->isPost) {
                $request = $request
                    ->post(
                        $this->method,
                        $this->params
                    )
                    ->throw();
            } else {
                $request = $request
                    ->get(
                        $this->method,
                        $this->params
                    )
                    ->throw();
            }

            if ((bool)$request->collect()->get('status') !== true) {
                throw new DeliveryException('API Warning: ' . print_r($request->collect()->get('message'),1));
            }

            return $request->collect($this->dataKey);

        } catch (RequestException $e) {
            throw new DeliveryException('API Exception: ' . $e->getMessage());
        }
    }

    private function hash(): string
    {
        $publicKey = Config::get('delivery-api.public_key');
        $secretKey = Config::get('delivery-api.secret_key');
        $timestamp = Carbon::now()->timestamp;

        return collect([
            $publicKey,
            $timestamp,
            hash_hmac('sha1', $publicKey . $timestamp, $secretKey),
        ])->implode(':');
    }

    public function cache(int $seconds = -1) : Collection
    {
        if ($seconds === -1) {
            return Cache::rememberForever($this->getCacheKey(), fn () => $this->make());
        }

        return Cache::remember($this->getCacheKey(), $seconds, fn () => $this->make());
    }

    private function getCacheKey() : string
    {
        return $this->method.'_'.collect($this->params)->values()->implode('_');
    }
}
