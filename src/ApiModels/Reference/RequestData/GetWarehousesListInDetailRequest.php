<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Reference\RequestData;

use Sashalenz\DeliveryAuto\Enums\Country;
use Sashalenz\DeliveryAuto\Enums\Culture;
use Spatie\LaravelData\Attributes\Validation\Uuid;
use Spatie\LaravelData\Data;

final class GetWarehousesListInDetailRequest extends Data
{
    public function __construct(
        public ?Culture $culture = null,
        #[Uuid]
        public ?string $CityId = null,
        public ?bool $onlyWarehouses = null,
        public ?bool $includeRegionalCenters = null,
        public ?bool $needCenterPickUpDelivery = null,
        public ?Country $country = null,
    ) {}
}
