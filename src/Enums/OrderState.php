<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\Enums;

/**
 * Pickup-order state codes (PDF v3.5.1 §8.5).
 */
enum OrderState: int
{
    case Open = 100000000;
    case Closed = 100000001;
    case Cancelled = 100000002;
    case CreatedByClient = 100000003;

    public function isOpen(): bool
    {
        return $this === self::Open || $this === self::CreatedByClient;
    }

    public function isCancelled(): bool
    {
        return $this === self::Cancelled;
    }

    public function label(): string
    {
        return match ($this) {
            self::Open => 'Відкрите',
            self::Closed => 'Закрите',
            self::Cancelled => 'Відмінене',
            self::CreatedByClient => 'Створене клієнтом',
        };
    }
}
