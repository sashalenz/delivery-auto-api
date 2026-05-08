<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Calculation\ResponseData;

use Spatie\LaravelData\Data;

final class InsuranceCostData extends Data
{
    public function __construct(
        public float $Value,
        public ?float $MinValue = null,
    ) {}
}
