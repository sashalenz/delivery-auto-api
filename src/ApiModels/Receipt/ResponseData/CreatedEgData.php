<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Receipt\ResponseData;

use Spatie\LaravelData\Data;

final class CreatedEgData extends Data
{
    public function __construct(
        public string $Id,
        public ?string $PartnerNumber = null,
        public ?string $Number = null,
    ) {}
}
