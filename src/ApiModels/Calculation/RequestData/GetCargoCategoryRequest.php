<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Calculation\RequestData;

use Sashalenz\DeliveryAuto\Enums\Culture;
use Spatie\LaravelData\Attributes\Validation\Uuid;
use Spatie\LaravelData\Data;

/**
 * §3.4 GetCargoCategory — public endpoint with optional TariffCategoryId filter.
 *
 * Pre-2.0 the package incorrectly marked this as auth-required and omitted
 * TariffCategoryId. PDF v3.5.1 §3.4 documents it as public + optional GUID.
 */
final class GetCargoCategoryRequest extends Data
{
    public function __construct(
        #[Uuid]
        public ?string $TariffCategoryId = null,
        public ?Culture $culture = null,
    ) {}
}
