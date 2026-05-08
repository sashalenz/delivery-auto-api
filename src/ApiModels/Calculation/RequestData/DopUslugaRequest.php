<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Calculation\RequestData;

use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Uuid;
use Spatie\LaravelData\Data;

final class DopUslugaRequest extends Data
{
    public function __construct(
        #[Required, Uuid]
        public string $uslugaId,
        #[Required, Min(1)]
        public int $count,
    ) {}
}
