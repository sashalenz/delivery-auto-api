<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\Enums;

/**
 * Filter for GetUserReceipt — list incoming vs outgoing receipts (PDF v3.5.1 §5.4).
 */
enum ReceiptListType: int
{
    case Outgoing = 0;
    case Incoming = 1;
}
