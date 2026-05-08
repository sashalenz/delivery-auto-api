<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Communication\RequestData;

use Sashalenz\DeliveryAuto\Enums\Culture;
use Spatie\LaravelData\Data;

final class GetMessagesThemeRequest extends Data
{
    public function __construct(
        public ?Culture $culture = null,
    ) {}
}
