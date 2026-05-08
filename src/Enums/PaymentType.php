<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\Enums;

/**
 * Payment type for receipts and insurance (PDF v3.5.1 §6.4).
 */
enum PaymentType: int
{
    case Cash = 0;
    case Cashless = 1;
}
