<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Reference\ResponseData;

use Sashalenz\DeliveryAuto\Enums\WarehouseType;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;

final class WarehouseFindData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public float $distance,
        public ?float $latitude = null,
        public ?float $longitude = null,
        public ?float $latitudeCorrect = null,
        public ?float $longitudeCorrect = null,
        public ?string $cityName = null,
        public ?string $address = null,
        public bool $IsWarehouse = false,
        public ?string $phone = null,
        public ?string $working_time = null,
        #[WithCast(EnumCast::class, WarehouseType::class)]
        public ?WarehouseType $WarehouseType = null,
        public bool $IsRegionalCentre = false,
        public ?int $Number = null,
    ) {}
}
