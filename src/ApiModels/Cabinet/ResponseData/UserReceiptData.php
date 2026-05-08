<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Cabinet\ResponseData;

use Carbon\Carbon;
use Sashalenz\DeliveryAuto\Casts\CarbonInterfaceCast;
use Sashalenz\DeliveryAuto\Enums\Currency;
use Sashalenz\DeliveryAuto\Enums\ReceiptStatus;
use Sashalenz\DeliveryAuto\Enums\ReceiptType;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

/**
 * §5.4 GetUserReceipt response item — TTN as seen by an authenticated user.
 */
final class UserReceiptData extends Data
{
    public function __construct(
        public string $id,
        public string $number,
        #[WithCast(CarbonInterfaceCast::class, format: ['Y-m-d\TH:i:s', 'Y-m-d\TH:i:sP'])]
        public ?Carbon $SendDate = null,
        #[WithCast(CarbonInterfaceCast::class, format: ['Y-m-d\TH:i:s', 'Y-m-d\TH:i:sP'])]
        public ?Carbon $ReceiveDate = null,
        public ?string $SenderWarehouseName = null,
        public ?string $RecepientWarehouseName = null,
        #[WithCast(EnumCast::class, ReceiptStatus::class)]
        public ?ReceiptStatus $Status = null,
        public ?string $StatusesDecoding = null,
        public ?float $TotalCost = null,
        public ?string $PartnerNumber = null,
        public ?float $Weight = null,
        public ?float $Volume = null,
        public ?bool $PaymentStatus = null,
        #[WithCast(EnumCast::class, Currency::class)]
        public ?Currency $Currency = null,
        public ?bool $CanChangeRecepient = null,
        public ?bool $LockShipping = null,
        public ?int $IsPrivate = null,
        public ?bool $IsAllowDeny = null,
        public ?string $Sender = null,
        public ?string $Recepient = null,
        public ?string $Payer = null,
        public ?float $StatedValue = null,
        public ?string $Sites = null,
        public ?float $PriceWarehouseWarehouse = null,
        public ?float $codCost = null,
        #[WithCast(EnumCast::class, Currency::class)]
        public ?Currency $codCurrency = null,
        public ?string $codName = null,
        public ?string $codPhone = null,
        public ?string $codWarehouse = null,
        /** @var DataCollection<int, AuxServiceData>|null */
        #[DataCollectionOf(AuxServiceData::class)]
        public ?DataCollection $AuxServicesList = null,
        public ?float $InsuranceCost = null,
        #[WithCast(EnumCast::class, Currency::class)]
        public ?Currency $InsuranceCurrency = null,
        /** @var DataCollection<int, PossibleReceiverData>|null */
        #[DataCollectionOf(PossibleReceiverData::class)]
        public ?DataCollection $PossibleReceivers = null,
        public ?int $PushStateCode = null,
        #[WithCast(EnumCast::class, ReceiptType::class)]
        public ?ReceiptType $Type = null,
    ) {}
}
