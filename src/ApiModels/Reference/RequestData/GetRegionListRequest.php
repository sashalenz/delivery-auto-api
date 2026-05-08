<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Reference\RequestData;

use Sashalenz\DeliveryAuto\Enums\Country;
use Sashalenz\DeliveryAuto\Enums\Culture;
use Spatie\LaravelData\Data;

final class GetRegionListRequest extends Data
{
    public function __construct(
        public ?Culture $culture = null,
        public ?Country $country = null,
    ) {}
}
