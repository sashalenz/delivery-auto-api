<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Calculation\RequestData;

use Sashalenz\DeliveryAuto\Enums\Culture;
use Sashalenz\DeliveryAuto\Enums\Currency;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Uuid;
use Spatie\LaravelData\Data;

final class GetDopUslugiClassificationRequest extends Data
{
    public function __construct(
        #[Required, Uuid]
        public string $CitySendId,
        #[Required, Uuid]
        public string $CityReceiveId,
        public Currency $currency = Currency::UAH,
        public ?Culture $culture = null,
        public ?bool $formalization = null,
    ) {}
}
