<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Reference\ResponseData;

use Sashalenz\DeliveryAuto\Enums\WarehouseType;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;

final class WarehouseInfoData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public ?string $address = null,
        public ?string $operatingTime = null,
        public ?string $Phone = null,
        public ?string $EmailStorage = null,
        public ?float $Latitude = null,
        public ?float $Longitude = null,
        #[MapInputName('latitudeCorrect')]
        public ?float $LatitudeCorrect = null,
        #[MapInputName('longitudeCorrect')]
        public ?float $LongitudeCorrect = null,
        public ?bool $Office = null,
        public ?string $CityId = null,
        public ?string $CityName = null,
        public ?bool $IsWarehouse = null,
        public ?string $RcPhoneSecurity = null,
        public ?string $RcPhoneManagers = null,
        public ?string $RcPhone = null,
        public ?string $RcName = null,
        public ?string $WarehouseForDeliveryId = null,
        public bool $IsCashOnDelivery = false,
        #[WithCast(EnumCast::class, WarehouseType::class)]
        public ?WarehouseType $WarehouseType = null,
        public bool $CenterPickUpDelivery = false,
        public ?int $Number = null,
    ) {}
}
