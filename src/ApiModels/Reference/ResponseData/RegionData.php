<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Reference\ResponseData;

use Spatie\LaravelData\Data;

final class RegionData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $externalId = null,
    ) {}
}
