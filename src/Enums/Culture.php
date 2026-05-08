<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\Enums;

/**
 * Supported response cultures (PDF v3.5.1, every endpoint's culture parameter).
 */
enum Culture: string
{
    case UkUA = 'uk-UA';
    case EnUS = 'en-US';
}
