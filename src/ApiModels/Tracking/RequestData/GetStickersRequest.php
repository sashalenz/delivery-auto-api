<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Tracking\RequestData;

use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

final class GetStickersRequest extends Data
{
    public function __construct(
        #[Required, StringType]
        public string $number,
    ) {}
}
