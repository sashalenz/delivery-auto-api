<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\Enums;

/**
 * Currency codes used by Delivery-Auto API (PDF v3.5.1 §8.2).
 */
enum Currency: int
{
    case UAH = 100000000;

    public function label(): string
    {
        return match ($this) {
            self::UAH => 'Гривня',
        };
    }
}
