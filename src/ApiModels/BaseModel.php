<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels;

use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Conditionable;
use Sashalenz\DeliveryAuto\Exceptions\DeliveryAutoException;
use Sashalenz\DeliveryAuto\Request;
use Spatie\LaravelData\Contracts\BaseData;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

/**
 * @phpstan-consistent-constructor
 */
abstract class BaseModel
{
    use Conditionable;

    protected ?string $calledMethod = null;

    protected array $params = [];

    protected bool $isPost = false;

    protected string $authMode = Request::AUTH_NONE;

    protected string $dataKey = 'data';

    protected bool $canBeCached = false;

    protected int $cacheSeconds = -1;

    protected ?string $publicKey = null;

    protected ?string $secretKey = null;

    public static function make(): static
    {
        return new static;
    }

    public function cache(int $seconds = -1): static
    {
        $this->canBeCached = true;
        $this->cacheSeconds = $seconds;

        return $this;
    }

    /**
     * Override the HMAC keypair for HMAC-protected calls. Useful when an app
     * holds multiple Delivery-Auto company keypairs (one per sender) and routes
     * a single request to a specific sender:
     *
     * ```
     * DeliveryAuto::receipt()
     *     ->withCredentials($sender->public_key, $sender->secret_key)
     *     ->postCreateReceipts($request);
     * ```
     *
     * Carries through {@see reset()} so sequential calls on the same builder
     * share the override. No effect on auth-less or login-session endpoints.
     */
    public function withCredentials(string $publicKey, string $secretKey): static
    {
        $this->publicKey = $publicKey;
        $this->secretKey = $secretKey;

        return $this;
    }

    /**
     * Return a clone of this builder with per-call state (method/params/auth/post/dataKey)
     * cleared, but caching preserved. Each public endpoint method on a module starts
     * with `$this->reset()->method(...)` so consumers can reuse a single module
     * instance across many calls without state leaking between them.
     */
    protected function reset(): static
    {
        $clone = clone $this;
        $clone->calledMethod = null;
        $clone->params = [];
        $clone->isPost = false;
        $clone->authMode = Request::AUTH_NONE;
        $clone->dataKey = 'data';

        return $clone;
    }

    protected function method(string $method): static
    {
        $this->calledMethod = $method;

        return $this;
    }

    protected function params(?Data $request = null): static
    {
        $this->params = $request === null ? [] : self::pruneNulls($request->toArray());

        return $this;
    }

    protected function rawParams(array $params): static
    {
        $this->params = self::pruneNulls($params);

        return $this;
    }

    protected function post(): static
    {
        $this->isPost = true;

        return $this;
    }

    protected function withHmac(): static
    {
        $this->authMode = Request::AUTH_HMAC;

        return $this;
    }

    protected function withSession(): static
    {
        $this->authMode = Request::AUTH_SESSION;

        return $this;
    }

    protected function dataKey(string $key): static
    {
        $this->dataKey = $key;

        return $this;
    }

    /**
     * @return Collection<array-key, mixed>
     *
     * @throws DeliveryAutoException
     */
    protected function request(): Collection
    {
        if ($this->calledMethod === null) {
            throw new DeliveryAutoException('API method not specified before request().');
        }

        $request = new Request(
            method: $this->calledMethod,
            params: $this->params,
            isPost: $this->isPost,
            authMode: $this->authMode,
            dataKey: $this->dataKey,
            publicKey: $this->publicKey,
            secretKey: $this->secretKey,
        );

        if ($this->canBeCached) {
            return $request->cache($this->cacheSeconds);
        }

        return $request->make();
    }

    /**
     * @return Collection<array-key, mixed>
     *
     * @throws DeliveryAutoException
     */
    protected function get(): Collection
    {
        return $this->request();
    }

    /**
     * @throws DeliveryAutoException
     */
    protected function first(): array
    {
        $first = $this->request()->first();

        return is_array($first) ? $first : [];
    }

    /**
     * @template T of BaseData
     *
     * @param  class-string<T>  $class
     * @return T|null
     *
     * @throws DeliveryAutoException
     */
    protected function toData(string $class): ?BaseData
    {
        $payload = $this->request();

        if ($payload->isEmpty()) {
            return null;
        }

        $first = $payload->first();

        if (is_array($first) && array_is_list($first) === false) {
            /** @var T */
            return $class::from($first);
        }

        /** @var T */
        return $class::from($payload->all());
    }

    /**
     * @template T of BaseData
     *
     * @param  class-string<T>  $class
     * @return DataCollection<int,T>
     *
     * @throws DeliveryAutoException
     */
    protected function toCollection(string $class): DataCollection
    {
        return new DataCollection($class, $this->request()->all());
    }

    /**
     * Drop null entries before sending — Delivery-Auto's GET endpoints treat
     * `?regionId=` (empty) as a real filter, not absent. Recursively prune
     * inside nested arrays so optional fields in POST bodies behave the same.
     */
    private static function pruneNulls(array $params): array
    {
        $out = [];

        foreach ($params as $key => $value) {
            if ($value === null) {
                continue;
            }

            if (is_array($value)) {
                $value = self::pruneNulls($value);
            }

            $out[$key] = $value;
        }

        return $out;
    }
}
