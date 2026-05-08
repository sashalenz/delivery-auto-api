<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\Enums;

/**
 * Receipt (TTN) status codes returned by GetReceiptDetails / GetUserReceipt.
 * PDF v3.5.1 §8.1.
 */
enum ReceiptStatus: int
{
    case Issued = 0;
    case PartiallyIssued = 1;
    case Formalized = 2;
    case Utilized = 3;
    case Sold = 4;
    case Cancelled = 5;
    case InTransit = 6;
    case AvailableForPickup = 7;
    case Reserved = 8;
    case ReAddressed = 9;
    case Unloading = 10;
    case ArrivedAtTransitWarehouse = 11;
    case PreparingForCourier = 12;
    case CourierDelivering = 13;

    /** Final, terminal states — no further transitions possible. */
    public function isFinal(): bool
    {
        return match ($this) {
            self::Issued, self::PartiallyIssued, self::Utilized, self::Sold, self::Cancelled => true,
            default => false,
        };
    }

    /** Successful delivery (cargo handed to recipient in full). */
    public function isSuccess(): bool
    {
        return $this === self::Issued;
    }

    /** Cargo is currently moving between warehouses. */
    public function isInTransit(): bool
    {
        return match ($this) {
            self::InTransit, self::ArrivedAtTransitWarehouse, self::Unloading,
            self::PreparingForCourier, self::CourierDelivering => true,
            default => false,
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::Issued => 'Видана',
            self::PartiallyIssued => 'Частково видана',
            self::Formalized => 'Оформлено',
            self::Utilized => 'Утилізовано',
            self::Sold => 'Продано',
            self::Cancelled => 'Скасовано',
            self::InTransit => 'В дорозі',
            self::AvailableForPickup => 'Доступна до видачі',
            self::Reserved => 'Зарезервована',
            self::ReAddressed => 'Переадресовано на інший склад',
            self::Unloading => 'На розвантаженні на складі',
            self::ArrivedAtTransitWarehouse => 'Вантаж прибув на транзитний склад',
            self::PreparingForCourier => "Готується до доставки кур'єром",
            self::CourierDelivering => "Доставляється кур'єром",
        };
    }
}
