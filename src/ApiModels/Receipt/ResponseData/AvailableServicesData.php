<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Receipt\ResponseData;

use Spatie\LaravelData\Data;

/**
 * §6.9 GetAvailableServices — boolean flags for which aux services are
 * available on a given route + delivery scheme + COD value.
 */
final class AvailableServicesData extends Data
{
    public function __construct(
        public bool $isInfo = false,
        public bool $isReturnDocs = false,
        public bool $isDenyIssue = false,
        public bool $isDescent = false,
        public bool $isLifting = false,
        public bool $isAutoReturn = false,
    ) {}
}
