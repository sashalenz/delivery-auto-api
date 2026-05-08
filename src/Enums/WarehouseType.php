<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\Enums;

/**
 * Warehouse type filter for GetFindWarehouses (PDF v3.5.1 §1.6).
 */
enum WarehouseType: int
{
    case Standard = 0;
    case CashTransferEnabled = 3;
}
