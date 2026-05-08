<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Cabinet\ResponseData;

use Carbon\Carbon;
use Sashalenz\DeliveryAuto\Casts\CarbonInterfaceCast;
use Sashalenz\DeliveryAuto\Enums\OrderState;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;

/**
 * §5.5 GetUserPickUp response item — pickup-order summary.
 */
final class UserPickUpData extends Data
{
    public function __construct(
        public ?string $Address = null,
        #[WithCast(CarbonInterfaceCast::class, format: ['Y-m-d\TH:i:s', 'Y-m-d\TH:i:sP'])]
        public ?Carbon $AppDate = null,
        public ?string $AppId = null,
        public ?string $AppNumber = null,
        public ?string $AppOrderId = null,
        #[WithCast(EnumCast::class, OrderState::class)]
        public ?OrderState $AppState = null,
        public ?PickUpTimeData $AppTime = null,
        public ?string $CityName = null,
        #[WithCast(CarbonInterfaceCast::class, format: ['Y-m-d\TH:i:s', 'Y-m-d\TH:i:sP'])]
        public ?Carbon $ClosedDate = null,
        public ?string $Comment = null,
        public ?string $ContactPhone = null,
        #[WithCast(CarbonInterfaceCast::class, format: ['Y-m-d\TH:i:s', 'Y-m-d\TH:i:sP'])]
        public ?Carbon $CreatedDate = null,
        public ?bool $HasReceipts = null,
        public ?string $PackComment = null,
        public ?bool $PickDeliv = null,
        public ?string $Places = null,
        public ?string $SenderPhone = null,
        public ?float $Volume = null,
        public ?float $Weight = null,
        public ?bool $additionalPacking = null,
        public ?string $addressText = null,
        public ?bool $canCancelOrder = null,
        public ?bool $cancelingInModeration = null,
        public ?string $cityText = null,
        public ?bool $dontCall = null,
        public ?int $fromInt = null,
        public ?bool $halfDiscount = null,
        public ?bool $hydrobort = null,
        public ?bool $isArchive = null,
        public ?bool $needAuto = null,
        public ?int $payType = null,
        public ?string $paymentStatus = null,
        public ?string $senderEmail = null,
        public ?string $senderName = null,
        public ?int $toInt = null,
        public ?float $totalCost = null,
    ) {}
}
