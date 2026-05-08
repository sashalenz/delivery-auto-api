<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\Enums;

/**
 * Delivery scheme codes (PDF v3.5.1 §3.5).
 */
enum DeliveryScheme: int
{
    case WarehouseWarehouse = 0;
    case DoorDoor = 1;
    case WarehouseDoor = 2;
    case DoorWarehouse = 3;

    public function requiresPickupAddress(): bool
    {
        return match ($this) {
            self::DoorDoor, self::DoorWarehouse => true,
            default => false,
        };
    }

    public function requiresDeliveryAddress(): bool
    {
        return match ($this) {
            self::DoorDoor, self::WarehouseDoor => true,
            default => false,
        };
    }

    public function requiresSenderWarehouse(): bool
    {
        return match ($this) {
            self::WarehouseWarehouse, self::WarehouseDoor => true,
            default => false,
        };
    }

    public function requiresReceiverWarehouse(): bool
    {
        return match ($this) {
            self::WarehouseWarehouse, self::DoorWarehouse => true,
            default => false,
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::WarehouseWarehouse => 'Склад-Склад',
            self::DoorDoor => 'Двері-Двері',
            self::WarehouseDoor => 'Склад-Двері',
            self::DoorWarehouse => 'Двері-Склад',
        };
    }
}
