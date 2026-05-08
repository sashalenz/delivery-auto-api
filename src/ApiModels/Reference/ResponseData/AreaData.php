<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Reference\ResponseData;

use Spatie\LaravelData\Data;

final class AreaData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public string $RegionId,
        public bool $IsWarehouse,
        public bool $ExtracityPickup,
        public bool $ExtracityShipping,
        public bool $RAP,
        public bool $RAS,
        public string $regionName,
        public int $regionId,
        public int $country,
        public ?string $districtName = null,
    ) {}
}
