<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Receipt\ResponseData;

use Spatie\LaravelData\Data;

final class ClientCardData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
    ) {}
}
