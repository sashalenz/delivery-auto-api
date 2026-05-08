<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Receipt\RequestData;

use DateTimeInterface;
use Sashalenz\DeliveryAuto\Transformers\CarbonInterfaceTransformer;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;

/**
 * §6.17 PostAddReceiptIntoPickUpRequest — combine multiple TTNs (created with
 * delivery-scheme 1 or 3) into a single pickup order at the same address.
 */
final class PostAddReceiptIntoPickUpRequest extends Data
{
    /**
     * @param  array<int,string>  $receiptNumberList
     */
    public function __construct(
        #[Required]
        public string $pickUpContactName,
        #[Required]
        public string $pickUpContactPhone,
        #[Required, WithTransformer(CarbonInterfaceTransformer::class, format: 'Y-m-d\TH:i:s')]
        public DateTimeInterface $pickUpDate,
        /** @var array<int,string> */
        #[Required]
        public array $receiptNumberList,
        public ?int $descentFromFloor = null,
    ) {}
}
