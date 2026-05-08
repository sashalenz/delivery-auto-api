<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Calculation\RequestData;

use Sashalenz\DeliveryAuto\Enums\Currency;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Uuid;
use Spatie\LaravelData\Data;

/**
 * §3.7 GetInsuranceCost — calculate insurance fee for a route + declared value.
 */
final class GetInsuranceCostRequest extends Data
{
    public function __construct(
        #[Required, Uuid]
        public string $WarehouseSendId,
        #[Required, Uuid]
        public string $WarehouseReceiveId,
        #[Required]
        public float $InsuranceValue,
        #[Required]
        public bool $PaymentType,
        #[Uuid]
        public ?string $CitySendId = null,
        #[Uuid]
        public ?string $CityReceiveId = null,
        public Currency $InsuranceCurrency = Currency::UAH,
    ) {}
}
