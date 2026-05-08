<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Cabinet\RequestData;

use Sashalenz\DeliveryAuto\Enums\Culture;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

/**
 * §6.14 GetFullReceiptInformation — full TTN information including aux services
 * and related receipts. Despite living in §6 of the PDF, this method requires
 * login-session auth (not HMAC), so it ships in the Cabinet module.
 */
final class GetFullReceiptInformationRequest extends Data
{
    public function __construct(
        #[Required, StringType]
        public string $number,
        public ?Culture $culture = null,
    ) {}
}
