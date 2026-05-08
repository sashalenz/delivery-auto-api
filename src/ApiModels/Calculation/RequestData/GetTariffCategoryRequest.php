<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Calculation\RequestData;

use Sashalenz\DeliveryAuto\Enums\Culture;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Uuid;
use Spatie\LaravelData\Data;

final class GetTariffCategoryRequest extends Data
{
    public function __construct(
        #[Required, Uuid]
        public string $CitySendId,
        #[Required, Uuid]
        public string $CityReceiveId,
        #[Required, Uuid]
        public string $WarehouseReceiveId,
        public ?Culture $culture = null,
    ) {}
}
