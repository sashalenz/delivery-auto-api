<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Calculation\ResponseData;

use Sashalenz\DeliveryAuto\Enums\Currency;
use Sashalenz\DeliveryAuto\Enums\DeliveryScheme;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

/**
 * §3.6 PostReceiptCalculate response — calculation totals + breakdown.
 */
final class CalculationData extends Data
{
    public function __construct(
        // Nullable — vendor returns `null` for `allSumma` together with
        // `status: false` on validation failures (e.g. invalid city /
        // warehouse UUIDs, out-of-network destinations, missing required
        // category fields). Strict `float`-typed constructor crashed
        // before the caller could even inspect `status`; consumers must
        // read `$status` first and ignore `$allSumma` when false.
        public ?float $allSumma,
        public bool $status,
        public ?float $SummaryTransportCost = null,
        public ?float $SummaryDuCost = null,
        public ?float $SummaryOformlenieCost = null,
        public ?float $EconomyVesovojTarifValuta = null,
        public ?float $EconomyEconomTarifValuta = null,
        public ?float $EconomySpecTarifValuta = null,
        public ?float $EconomyIndTarifValuta = null,
        public ?float $EconomyGlobalDiscountValuta = null,
        public ?float $EconomyDiscountCardValuta = null,
        public ?float $EconomyAktsiyaValuta = null,
        public ?float $EconomyOnAmountSkidka = null,
        public ?float $EconomySummary = null,
        public ?float $ComissionGM = null,
        public ?string $culture = null,
        public ?string $calcType = null,
        public ?string $areasSendId = null,
        public ?string $areasResiveId = null,
        public ?string $warehouseSendId = null,
        public ?string $individualTarifId = null,
        public ?string $warehouseResiveId = null,
        public ?string $areasSendIdName = null,
        public ?string $areasResiveIdName = null,
        public ?string $warehouseSendIdName = null,
        public ?string $warehouseResiveIdName = null,
        public ?float $InsuranceValue = null,
        public ?float $InsuranceCost = null,
        public ?string $dateSend = null,
        public ?string $dateResive = null,
        #[WithCast(EnumCast::class, DeliveryScheme::class)]
        public ?DeliveryScheme $deliveryScheme = null,
        public ?float $categorySumma = null,
        public ?string $comment = null,
        public ?int $viewType = null,
        #[WithCast(EnumCast::class, Currency::class)]
        public ?Currency $currency = null,
        /** @var DataCollection<int, CategoryCalculationData>|null */
        #[DataCollectionOf(CategoryCalculationData::class)]
        public ?DataCollection $category = null,
        /** @var DataCollection<int, DopUslugaClassificationData>|null */
        #[DataCollectionOf(DopUslugaClassificationData::class)]
        public ?DataCollection $dopUslugaClassificator = null,
    ) {}
}
