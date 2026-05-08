<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Cabinet\ResponseData;

use Spatie\LaravelData\Data;

final class PossibleReceiverData extends Data
{
    public function __construct(
        public ?string $Id = null,
        public ?string $ReceiptId = null,
        public ?string $Name = null,
    ) {}
}
