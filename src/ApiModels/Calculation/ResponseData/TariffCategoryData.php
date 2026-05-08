<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Calculation\ResponseData;

use Spatie\LaravelData\Data;

final class TariffCategoryData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public ?float $MinWidth = null,
        public ?float $MaxWidth = null,
        public ?float $MinSize = null,
        public ?float $MaxSize = null,
        public ?float $Length = null,
        public ?float $Width = null,
        public ?float $Height = null,
        public ?bool $RequiredWeight = null,
        public ?bool $RequiredSize = null,
    ) {}
}
