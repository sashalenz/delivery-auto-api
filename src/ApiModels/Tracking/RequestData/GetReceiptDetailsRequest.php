<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Tracking\RequestData;

use Sashalenz\DeliveryAuto\Enums\Culture;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

final class GetReceiptDetailsRequest extends Data
{
    public function __construct(
        #[Required, StringType]
        public string $number,
        public ?Culture $culture = null,
    ) {}
}
