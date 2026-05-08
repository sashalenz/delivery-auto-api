<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\Enums;

/**
 * Country codes accepted by Delivery-Auto API.
 *
 * The PDF only enumerates Ukraine (`country=1`); the parameter is documented as
 * "1 — Україна, null — усі". This enum is single-case for now but kept as
 * an enum so request DTOs can type the parameter strictly and we have a
 * grow path if the vendor expands coverage.
 */
enum Country: int
{
    case UA = 1;

    public function label(): string
    {
        return match ($this) {
            self::UA => 'Україна',
        };
    }
}
