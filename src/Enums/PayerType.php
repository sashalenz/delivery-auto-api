<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\Enums;

/**
 * Payer type for receipts (PDF v3.5.1 §6.4 / §6.10).
 */
enum PayerType: int
{
    case Sender = 0;
    case Receiver = 1;
    case ThirdParty = 2;
}
