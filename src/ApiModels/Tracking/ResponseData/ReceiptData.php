<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Tracking\ResponseData;

use Carbon\Carbon;
use Sashalenz\DeliveryAuto\Casts\CarbonInterfaceCast;
use Sashalenz\DeliveryAuto\Enums\Currency;
use Sashalenz\DeliveryAuto\Enums\ReceiptStatus;
use Sashalenz\DeliveryAuto\Enums\ReceiptType;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;

/**
 * §2.1 GetReceiptDetails — public TTN tracking data.
 */
final class ReceiptData extends Data
{
    public function __construct(
        public string $id,
        public string $number,
        #[WithCast(CarbonInterfaceCast::class, format: ['Y-m-d\TH:i:s', 'Y-m-d\TH:i:sP'])]
        public ?Carbon $SendDate = null,
        #[WithCast(CarbonInterfaceCast::class, format: ['Y-m-d\TH:i:s', 'Y-m-d\TH:i:sP'])]
        public ?Carbon $ReceiveDate = null,
        #[WithCast(CarbonInterfaceCast::class, format: ['Y-m-d\TH:i:s', 'Y-m-d\TH:i:sP'])]
        public ?Carbon $CreatedDate = null,
        public ?string $SenderWarehouseName = null,
        public ?string $RecepientWarehouseName = null,
        public ?float $Discount = null,
        public ?float $TotalCost = null,
        #[WithCast(EnumCast::class, ReceiptStatus::class)]
        public ?ReceiptStatus $Status = null,
        public ?float $Weight = null,
        public ?float $Volume = null,
        public ?string $Sites = null,
        public ?string $cargoCategory = null,
        public ?bool $PaymentStatus = null,
        #[WithCast(EnumCast::class, Currency::class)]
        public ?Currency $Currency = null,
        public ?float $InsuranceCost = null,
        public ?float $InsuranceValue = null,
        #[WithCast(EnumCast::class, Currency::class)]
        public ?Currency $InsuranceCurrency = null,
        public ?int $PushStateCode = null,
        public ?float $codCost = null,
        #[WithCast(EnumCast::class, Currency::class)]
        public ?Currency $codCurrency = null,
        #[WithCast(EnumCast::class, ReceiptType::class)]
        public ?ReceiptType $Type = null,
        public ?string $Mainbillid = null,
        public ?string $Mainbill = null,
        public ?string $SenderPhone = null,
        public ?string $ReceiverPhone = null,
        public ?string $CitySendName = null,
        public ?string $CityReceiveName = null,
        public ?int $DeliveryType = null,
        public ?string $StatusesDecoding = null,
        public ?string $codSender = null,
        public ?string $SafetyDealMoneyStatus = null,
        public ?string $InsuranceInfo = null,
        public ?Carbon $DateArrivalExpress = null,
    ) {}
}
