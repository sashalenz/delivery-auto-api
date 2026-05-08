<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Receipt\RequestData;

use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Uuid;
use Spatie\LaravelData\Data;

final class GetClientPaymentTypeRequest extends Data
{
    public function __construct(
        #[Required, Uuid]
        public string $ClientId,
    ) {}
}
