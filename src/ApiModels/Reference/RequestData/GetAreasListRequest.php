<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Reference\RequestData;

use Sashalenz\DeliveryAuto\Enums\Country;
use Sashalenz\DeliveryAuto\Enums\Culture;
use Spatie\LaravelData\Attributes\Validation\Uuid;
use Spatie\LaravelData\Data;

final class GetAreasListRequest extends Data
{
    public function __construct(
        public ?Culture $culture = null,
        public ?bool $fl_all = null,
        #[Uuid]
        public ?string $regionId = null,
        public ?Country $country = null,
        public ?string $cityName = null,
    ) {}
}
