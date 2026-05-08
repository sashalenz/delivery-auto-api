<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Communication\ResponseData;

use Spatie\LaravelData\Data;

final class MessageThemeData extends Data
{
    public function __construct(
        public string $Id,
        public string $Name,
    ) {}
}
