<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Reference\RequestData;

use Sashalenz\DeliveryAuto\Enums\Culture;
use Sashalenz\DeliveryAuto\Enums\DirectionType;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Uuid;
use Spatie\LaravelData\Data;

final class GetWarehousesListByCityRequest extends Data
{
    public function __construct(
        #[Required, Uuid]
        public string $CityId,
        #[Required]
        public DirectionType $DirectionType,
        public ?Culture $culture = null,
    ) {}
}
