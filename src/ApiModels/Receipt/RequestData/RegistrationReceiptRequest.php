<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Receipt\RequestData;

use DateTimeInterface;
use Sashalenz\DeliveryAuto\ApiModels\Calculation\RequestData\CategoryRequest;
use Sashalenz\DeliveryAuto\Enums\CashOnDeliveryType;
use Sashalenz\DeliveryAuto\Enums\Currency;
use Sashalenz\DeliveryAuto\Enums\DeliveryScheme;
use Sashalenz\DeliveryAuto\Enums\PayerType;
use Sashalenz\DeliveryAuto\Enums\PaymentType;
use Sashalenz\DeliveryAuto\Transformers\CarbonInterfaceTransformer;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Uuid;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

/**
 * §6.4 PostCreateReceipts — single receipt slot inside `receiptsList`.
 *
 * Implements the full ~50 field RegistrationReceiptsModel from PDF v3.5.1
 * §6.4 (pp.47–55). Required-conditional fields are documented below; the
 * vendor will reject the receipt with a populated `message` if required
 * combinations are missing.
 */
final class RegistrationReceiptRequest extends Data
{
    public function __construct(
        #[Required, Uuid]
        public string $areasSendId,
        #[Required, Uuid]
        public string $areasResiveId,
        #[Required, WithTransformer(CarbonInterfaceTransformer::class, format: 'Y-m-d\TH:i:s')]
        public DateTimeInterface $dateSend,
        /** @var DataCollection<int, CategoryRequest> */
        #[DataCollectionOf(CategoryRequest::class), Required]
        public DataCollection $category,
        public DeliveryScheme $deliveryScheme = DeliveryScheme::WarehouseWarehouse,
        // Required when deliveryScheme = 0 or 2 (sender warehouse needed)
        #[Uuid]
        public ?string $warehouseSendId = null,
        // Required when deliveryScheme = 0 or 3 (receiver warehouse needed)
        #[Uuid]
        public ?string $warehouseResiveId = null,
        // Required when deliveryScheme = 1 or 3 (door pickup)
        public ?string $pickUpContactName = null,
        public ?string $pickUpContactPhone = null,
        #[Uuid]
        public ?string $pickUpAddressId = null,
        public ?string $pickUpAddress = null,
        #[WithTransformer(CarbonInterfaceTransformer::class, format: 'Y-m-d\TH:i:s')]
        public ?DateTimeInterface $pickUpDate = null,
        // Required when deliveryScheme = 1 or 2 (door delivery)
        public ?string $deliveryContactName = null,
        public ?string $deliveryContactPhone = null,
        public ?string $DeliveryComment = null,
        #[Uuid]
        public ?string $deliveryAddressId = null,
        public ?string $deliveryAddress = null,
        // Sender — defaults to API key owner
        #[Uuid]
        public ?string $SenderId = null,
        // Receiver — pass an existing receiver UUID, or create a new one with the four fields below
        #[Uuid]
        public ?string $posibleReceiverReceipt_1 = null,
        public ?string $receiverName = null,
        public bool $receiverType = false,
        public ?string $receiverPhone = null,
        public ?string $receiverEgrpo = null,
        // Optional additional receiver UUIDs
        #[Uuid]
        public ?string $posibleReceiverReceipt_2 = null,
        #[Uuid]
        public ?string $posibleReceiverReceipt_3 = null,
        #[Uuid]
        public ?string $posibleReceiverReceipt_4 = null,
        public Currency $currency = Currency::UAH,
        public PayerType $payerType = PayerType::Sender,
        public PaymentType $paymentType = PaymentType::Cash,
        #[Uuid]
        public ?string $payerId = null,
        public PaymentType $paymentTypeInsuranse = PaymentType::Cash,
        #[Uuid]
        public ?string $payerInsuranceId = null,
        public float $InsuranceValue = 0,
        // Cash-on-delivery (COD) — required when CashOnDeliveryValue > 0
        #[Uuid]
        public ?string $CashOnDeliveryPayerAccountId = null,
        public ?float $cashOnDeliveryValue = null,
        public Currency $cashOnDeliveryValuta = Currency::UAH,
        public CashOnDeliveryType $cashOnDeliveryType = CashOnDeliveryType::Card,
        #[Uuid]
        public ?string $CashOnDeliveryWarehouseId = null,
        #[Uuid]
        public ?string $CashOnDeliveryRasschSchetId = null,
        #[Uuid]
        public ?string $CashOnDeliveryCardId = null,
        public ?string $CashOnDeliverySenderPhone = null,
        public ?string $CashOnDeliverySenderFullName = null,
        public ?string $CashOnDeliveryReceiverPhone = null,
        public ?string $CashOnDeliveryReceiverFullName = null,
        public ?string $CashOnDeliveryDescription = null,
        public ?bool $denyIssue = null,
        public ?string $denyIssuePhone = null,
        public ?bool $ReturnDocuments = null,
        public ?int $descentFromFloor = null,
        public ?int $climbingToFloor = null,
        public ?bool $IsOverSize = null,
        public ?bool $IsGidrobort = null,
        public ?bool $EconomDelivery = null,
        public ?bool $EconomPickUp = null,
        public ?bool $ExpressPickUp = null,
        public ?string $parentNumber = null,
        public ?string $Coupon = null,
        public ?string $Note = null,
    ) {}
}
