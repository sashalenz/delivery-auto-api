<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Reference\ResponseData;

use Sashalenz\DeliveryAuto\Enums\WarehouseType;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;

final class WarehouseData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public ?string $address = null,
        public ?float $Latitude = null,
        public ?float $Longitude = null,
        public ?string $CityId = null,
        public ?float $LatitudeCorrect = null,
        public ?float $LongitudeCorrect = null,
        public bool $IsCashOnDelivery = false,
        public bool $CenterPickUpDelivery = false,
        #[WithCast(EnumCast::class, WarehouseType::class)]
        public ?WarehouseType $warehouseType = null,
        public bool $IsFranchising = false,
        public ?int $Number = null,
    ) {}
}
