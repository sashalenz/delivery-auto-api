<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Calculation\ResponseData;

use Spatie\LaravelData\Data;

final class CargoCategoryData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
    ) {}
}
