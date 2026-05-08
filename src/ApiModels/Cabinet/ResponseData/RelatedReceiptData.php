<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Cabinet\ResponseData;

use Carbon\Carbon;
use Sashalenz\DeliveryAuto\Casts\CarbonInterfaceCast;
use Sashalenz\DeliveryAuto\Enums\Currency;
use Sashalenz\DeliveryAuto\Enums\PaymentType;
use Sashalenz\DeliveryAuto\Enums\ReceiptStatus;
use Sashalenz\DeliveryAuto\Enums\ReceiptType;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;

final class RelatedReceiptData extends Data
{
    public function __construct(
        public string $number,
        #[WithCast(EnumCast::class, ReceiptStatus::class)]
        public ?ReceiptStatus $state = null,
        #[WithCast(EnumCast::class, ReceiptType::class)]
        public ?ReceiptType $receiptType = null,
        #[WithCast(EnumCast::class, PaymentType::class)]
        public ?PaymentType $paymentType = null,
        public ?bool $paymentStatus = null,
        #[WithCast(CarbonInterfaceCast::class, format: ['Y-m-d\TH:i:s', 'Y-m-d\TH:i:sP'])]
        public ?Carbon $paymentDate = null,
        #[WithCast(EnumCast::class, Currency::class)]
        public ?Currency $currency = null,
        public ?string $payerId = null,
        public ?string $payer = null,
        public ?float $totalAmount = null,
        public ?string $clientCardId = null,
        public ?string $clientCard = null,
        public ?string $codSender = null,
        public ?string $codSenderPhone = null,
        public ?bool $isGiveMoney = null,
        public ?string $codWarehouse = null,
        public ?string $codCity = null,
    ) {}
}
