<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Calculation\ResponseData;

use Sashalenz\DeliveryAuto\Enums\Currency;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;

final class DopUslugaData extends Data
{
    public function __construct(
        public string $uslugaId,
        public string $name,
        public ?float $cost = null,
        public ?int $count = null,
        public ?int $classification = null,
        public ?float $minWidth = null,
        public ?float $maxWidth = null,
        public ?float $summa = null,
        public ?string $comment = null,
        #[WithCast(EnumCast::class, Currency::class)]
        public ?Currency $currency = null,
    ) {}
}
