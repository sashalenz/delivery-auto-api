<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Receipt\ResponseData;

use Spatie\LaravelData\Data;

final class ClientInvoiceData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public ?string $ownerId = null,
        public ?string $ownerName = null,
    ) {}
}
