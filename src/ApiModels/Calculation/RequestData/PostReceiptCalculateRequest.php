<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Calculation\RequestData;

use DateTimeInterface;
use Sashalenz\DeliveryAuto\Enums\Culture;
use Sashalenz\DeliveryAuto\Enums\Currency;
use Sashalenz\DeliveryAuto\Enums\DeliveryScheme;
use Sashalenz\DeliveryAuto\Transformers\CarbonInterfaceTransformer;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Uuid;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Optional;

/**
 * §3.6 PostReceiptCalculate — calculate shipment cost.
 *
 * The "CalculatorModel" in the PDF (§3.1) — request body for the calculator.
 */
final class PostReceiptCalculateRequest extends Data
{
    public function __construct(
        #[Required, Uuid]
        public string $areasSendId,
        #[Required, Uuid]
        public string $areasResiveId,
        #[Required, Uuid]
        public string $warehouseSendId,
        #[Required, Uuid]
        public string $warehouseResiveId,
        #[Required, Min(0)]
        public float $InsuranceValue,
        #[Required, Min(0)]
        public float $CashOnDeliveryValue,
        #[Required, WithTransformer(CarbonInterfaceTransformer::class, format: 'd.m.Y')]
        public DateTimeInterface $dateSend,
        #[Required]
        public DeliveryScheme $deliveryScheme,
        /** @var DataCollection<int, CategoryRequest> */
        #[DataCollectionOf(CategoryRequest::class), Required]
        public DataCollection $category,
        #[Uuid]
        public Optional|string|null $senderId = null,
        public ?Culture $culture = null,
        public Currency $currency = Currency::UAH,
        public Currency $CashOnDeliveryValuta = Currency::UAH,
        /** @var DataCollection<int, DopUslugaClassificatorRequest>|null */
        #[DataCollectionOf(DopUslugaClassificatorRequest::class)]
        public ?DataCollection $dopUslugaClassificator = null,
        public ?int $climbingToFloor = null,
        public ?int $descentFromFloor = null,
    ) {}
}
