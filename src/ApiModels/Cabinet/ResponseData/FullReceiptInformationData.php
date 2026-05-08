<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Cabinet\ResponseData;

use Carbon\Carbon;
use Sashalenz\DeliveryAuto\Casts\CarbonInterfaceCast;
use Sashalenz\DeliveryAuto\Enums\Currency;
use Sashalenz\DeliveryAuto\Enums\DeliveryScheme;
use Sashalenz\DeliveryAuto\Enums\PaymentType;
use Sashalenz\DeliveryAuto\Enums\ReceiptStatus;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

/**
 * §6.14 GetFullReceiptInformation — full TTN information.
 *
 * Response is a single object with `duArray`, `possibleReceiverArray`,
 * `receiptsArray`, `egArray` — each a typed sub-collection.
 */
final class FullReceiptInformationData extends Data
{
    public function __construct(
        public string $number,
        public ?string $areasSendId = null,
        public ?string $areasSend = null,
        public ?string $areasReceiveId = null,
        public ?string $areasResive = null,
        public ?string $warehouseSendId = null,
        public ?string $warhouseSend = null,
        public ?string $warehouseReceiveId = null,
        public ?string $warhouseReceive = null,
        #[WithCast(EnumCast::class, DeliveryScheme::class)]
        public ?DeliveryScheme $deliveryScheme = null,
        public ?string $sender = null,
        public ?string $senderId = null,
        public ?string $senderEgrpo = null,
        public ?string $receiver = null,
        public ?string $receiverId = null,
        public ?string $receiverEgrpo = null,
        public ?string $payer = null,
        public ?string $payerId = null,
        public ?string $payerEgrpo = null,
        #[WithCast(EnumCast::class, PaymentType::class)]
        public ?PaymentType $paymentType = null,
        #[WithCast(CarbonInterfaceCast::class, format: ['Y-m-d\TH:i:s', 'Y-m-d\TH:i:sP'])]
        public ?Carbon $dateSend = null,
        #[WithCast(CarbonInterfaceCast::class, format: ['Y-m-d\TH:i:s', 'Y-m-d\TH:i:sP'])]
        public ?Carbon $dateReceive = null,
        #[WithCast(EnumCast::class, ReceiptStatus::class)]
        public ?ReceiptStatus $state = null,
        #[WithCast(EnumCast::class, Currency::class)]
        public ?Currency $Currency = null,
        public ?string $partnerNumber = null,
        public ?bool $paymentStatus = null,
        #[WithCast(CarbonInterfaceCast::class, format: ['Y-m-d\TH:i:s', 'Y-m-d\TH:i:sP'])]
        public ?Carbon $paymentDate = null,
        public ?bool $lockShipping = null,
        public ?string $totalCountPlace = null,
        public ?float $totalWeight = null,
        public ?float $totalSize = null,
        public ?float $warehouseWarehouseAmount = null,
        public ?float $discountAmount = null,
        public ?float $lossesDescountAmount = null,
        public ?float $totalAmount = null,
        public ?float $insuranceValue = null,
        public ?string $SafetyDealMoneyStatus = null,
        /** @var DataCollection<int, AuxServiceData>|null */
        #[DataCollectionOf(AuxServiceData::class)]
        public ?DataCollection $duArray = null,
        /** @var DataCollection<int, PossibleReceiverData>|null */
        #[DataCollectionOf(PossibleReceiverData::class)]
        public ?DataCollection $possibleReceiverArray = null,
        /** @var DataCollection<int, RelatedReceiptData>|null */
        #[DataCollectionOf(RelatedReceiptData::class)]
        public ?DataCollection $receiptsArray = null,
        /** @var DataCollection<int, CargoUnitData>|null */
        #[DataCollectionOf(CargoUnitData::class)]
        public ?DataCollection $egArray = null,
    ) {}
}
