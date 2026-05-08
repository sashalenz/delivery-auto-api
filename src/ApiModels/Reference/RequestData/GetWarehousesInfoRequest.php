<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Reference\RequestData;

use Sashalenz\DeliveryAuto\Enums\Culture;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Uuid;
use Spatie\LaravelData\Data;

final class GetWarehousesInfoRequest extends Data
{
    public function __construct(
        #[Required, Uuid]
        public string $WarehousesId,
        public ?Culture $culture = null,
    ) {}
}
