<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\Enums;

/**
 * Warehouse direction filter for GetWarehousesListByCity (PDF v3.5.1 §1.5).
 */
enum DirectionType: int
{
    case Send = 0;
    case Receive = 1;
}
