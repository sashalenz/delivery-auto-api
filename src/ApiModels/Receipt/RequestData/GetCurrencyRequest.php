<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Receipt\RequestData;

use Sashalenz\DeliveryAuto\Enums\Culture;
use Sashalenz\DeliveryAuto\Enums\PayerType;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Uuid;
use Spatie\LaravelData\Data;

final class GetCurrencyRequest extends Data
{
    public function __construct(
        #[Required, Uuid]
        public string $CitySendId,
        #[Required, Uuid]
        public string $CityReceiveId,
        public PayerType $PayerType = PayerType::Sender,
        #[Uuid]
        public ?string $PayerId = null,
        public ?Culture $culture = null,
    ) {}
}
