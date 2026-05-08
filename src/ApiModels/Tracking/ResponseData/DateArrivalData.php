<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Tracking\ResponseData;

use Carbon\Carbon;
use Sashalenz\DeliveryAuto\Casts\CarbonInterfaceCast;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

/**
 * §2.2 GetDateArrival — delivery ETA estimate.
 */
final class DateArrivalData extends Data
{
    public function __construct(
        #[WithCast(CarbonInterfaceCast::class, format: ['Y-m-d\TH:i:sP', 'Y-m-d\TH:i:s'])]
        public ?Carbon $arrivalDate = null,
        #[WithCast(CarbonInterfaceCast::class, format: ['Y-m-d\TH:i:sP', 'Y-m-d\TH:i:s'])]
        public ?Carbon $sendDate = null,
        public ?float $weightSummary = null,
        public ?float $volumeSummary = null,
        public ?string $arrivalDateStr = null,
        public ?string $sendDateStr = null,
    ) {}
}
