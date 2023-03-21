<?php

namespace Sashalenz\DeliveryAuto\ApiModels;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Sashalenz\DeliveryAuto\Exceptions\DeliveryException;
use Sashalenz\DeliveryAuto\Request;

abstract class BaseModel
{
    private bool $canBeCached = false;
    private int $cacheSeconds = -1;
    private bool $authRequired = false;
    private bool $isPost = false;
    private string $dataKey = 'data';

    private array $params = [];
    private ?string $method = null;

    public function __construct()
    {
        $this->params['culture'] = Config::get('delivery-api.culture');
    }

    public function cache(int $seconds = -1) : self
    {
        $this->canBeCached = true;
        $this->cacheSeconds = $seconds;

        return $this;
    }

    public function debug(): self
    {
        $this->params['debugMode'] = true;

        return $this;
    }

    public function auth(): self
    {
        $this->authRequired = true;

        return $this;
    }

    public function post(): self
    {
        $this->isPost = true;

        return $this;
    }

    public function dataKey(string $dataKey): self
    {
        $this->dataKey = $dataKey;

        return $this;
    }

    protected function method(string $method) : self
    {
        $this->method = $method;

        return $this;
    }

    public function params(array $params): self
    {
        $this->params = array_merge($this->params, $params);

        return $this;
    }

    public function culture(string $culture): self
    {
        $this->params['culture'] = $culture;

        return $this;
    }

    public function country(int $countryId): self
    {
        $this->params['country'] = $countryId;

        return $this;
    }

    /**
     * @param array $rules
     * @return $this
     * @throws DeliveryException
     */
    protected function validate(array $rules = []): self
    {
        $validator = Validator::make(
            $this->params,
            $rules
        );

        if ($validator->fails()) {
            throw new DeliveryException('Validation exception ' . $validator->errors()->first());
        }

        return $this;
    }

    /**
     * @return Collection
     * @throws DeliveryException
     */
    public function request() : Collection
    {
        if (is_null($this->method)) {
            throw new DeliveryException('API Exception: Provide method first');
        }

        $request = new Request(
            $this->method,
            $this->params,
            $this->authRequired,
            $this->dataKey,
            $this->isPost
        );

        if ($this->canBeCached) {
            return $request->cache($this->cacheSeconds);
        }

        return $request->make();
    }
}
