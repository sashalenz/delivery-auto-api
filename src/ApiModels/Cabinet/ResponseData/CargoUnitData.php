<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Cabinet\ResponseData;

use Spatie\LaravelData\Data;

final class CargoUnitData extends Data
{
    public function __construct(
        public ?string $cargoCregoryId = null,
        public ?string $cargoCregory = null,
        public ?int $count = null,
        public ?float $weight = null,
        public ?float $size = null,
        public ?bool $isEconomy = null,
        public ?float $cost = null,
    ) {}
}
