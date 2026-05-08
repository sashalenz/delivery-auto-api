<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Cabinet\ResponseData;

use Spatie\LaravelData\Data;

final class PickUpTimeData extends Data
{
    public function __construct(
        public ?int $from = null,
        public ?int $to = null,
    ) {}
}
