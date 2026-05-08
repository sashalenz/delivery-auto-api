<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Cabinet\RequestData;

use Sashalenz\DeliveryAuto\Enums\Culture;
use Spatie\LaravelData\Data;

final class GetUserInfoRequest extends Data
{
    public function __construct(
        public ?Culture $culture = null,
    ) {}
}
