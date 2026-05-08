<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Calculation\ResponseData;

use Sashalenz\DeliveryAuto\Enums\DeliveryScheme;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;

final class DeliverySchemeData extends Data
{
    public function __construct(
        #[WithCast(EnumCast::class, DeliveryScheme::class)]
        public DeliveryScheme $id,
        public string $name,
    ) {}
}
