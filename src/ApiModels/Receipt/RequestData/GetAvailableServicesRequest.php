<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Receipt\RequestData;

use Sashalenz\DeliveryAuto\Enums\DeliveryScheme;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Uuid;
use Spatie\LaravelData\Data;

final class GetAvailableServicesRequest extends Data
{
    public function __construct(
        #[Required]
        public DeliveryScheme $scheme,
        #[Required, Uuid]
        public string $receiveWarehouseId,
        public ?float $CodValue = null,
    ) {}
}
