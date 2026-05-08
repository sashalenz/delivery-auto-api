<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Reference\RequestData;

use Sashalenz\DeliveryAuto\Enums\Country;
use Sashalenz\DeliveryAuto\Enums\Culture;
use Spatie\LaravelData\Attributes\Validation\Uuid;
use Spatie\LaravelData\Data;

final class GetWarehousesListRequest extends Data
{
    public function __construct(
        public ?Culture $culture = null,
        public ?bool $includeRegionalCenters = null,
        public ?bool $needCenterPickUpDelivery = null,
        #[Uuid]
        public ?string $CityId = null,
        #[Uuid]
        public ?string $RegionId = null,
        public ?Country $country = null,
    ) {}
}
