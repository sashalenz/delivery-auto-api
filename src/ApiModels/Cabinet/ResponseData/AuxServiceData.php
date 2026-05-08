<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Cabinet\ResponseData;

use Spatie\LaravelData\Data;

final class AuxServiceData extends Data
{
    public function __construct(
        public string $Id,
        public ?string $ReceiptId = null,
        public ?string $Name = null,
        public ?int $Count = null,
        public ?float $Summ = null,
        public ?string $addressId = null,
        public ?string $address = null,
        public ?int $type = null,
    ) {}
}
