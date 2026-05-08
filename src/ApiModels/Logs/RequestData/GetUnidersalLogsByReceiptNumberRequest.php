<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Logs\RequestData;

use Sashalenz\DeliveryAuto\Enums\Culture;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

/**
 * §7.1 GetUnidersalLogsByReceiptNumber. Note the typo "Unidersal" — preserved
 * as-is because that is the actual API method name. (Verified against PDF v3.5.1.)
 */
final class GetUnidersalLogsByReceiptNumberRequest extends Data
{
    public function __construct(
        #[Required, StringType]
        public string $number,
        public ?Culture $culture = null,
    ) {}
}
