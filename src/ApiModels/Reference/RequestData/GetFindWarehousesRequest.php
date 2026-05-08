<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Reference\RequestData;

use Sashalenz\DeliveryAuto\Enums\Country;
use Sashalenz\DeliveryAuto\Enums\Culture;
use Sashalenz\DeliveryAuto\Enums\WarehouseType;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Uuid;
use Spatie\LaravelData\Data;

final class GetFindWarehousesRequest extends Data
{
    public function __construct(
        #[Required]
        public float $Longitude,
        #[Required]
        public float $Latitude,
        #[Min(1)]
        public ?int $count = null,
        public ?Culture $culture = null,
        public ?bool $includeRegionalCenters = null,
        #[Uuid]
        public ?string $CityId = null,
        public ?WarehouseType $Type = null,
        public ?Country $country = null,
    ) {}
}
