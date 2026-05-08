<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Communication\RequestData;

use Sashalenz\DeliveryAuto\Enums\Culture;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;

final class GetNewsRequest extends Data
{
    public function __construct(
        public ?Culture $culture = null,
        #[Min(1)]
        public int $count = 1,
        #[Min(1)]
        public int $page = 1,
    ) {}
}
