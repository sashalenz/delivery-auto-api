<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Receipt\RequestData;

use Sashalenz\DeliveryAuto\Enums\PayerType;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Uuid;
use Spatie\LaravelData\Data;

final class GetPayerRequest extends Data
{
    public function __construct(
        #[Required, Uuid]
        public string $CitySendId,
        #[Required, Uuid]
        public string $CityReceiveId,
        #[Required, Uuid]
        public string $ClientSenderId,
        #[Uuid]
        public ?string $ClientReceiverId = null,
        public ?PayerType $PayerType = null,
    ) {}
}
