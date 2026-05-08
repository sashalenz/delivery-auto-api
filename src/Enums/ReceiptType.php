<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\Enums;

/**
 * Receipt type codes (PDF v3.5.1 §8.4).
 */
enum ReceiptType: int
{
    case Regular = 2;
    case ReAddress = 4;
    case Delivery = 5;
    case Insurance = 6;
    case CargoPickup = 7;
    case ServiceSale = 8;
    case CardTransfer = 10;
    case CourierDelivery = 11;
    case CashOnDelivery = 13;
    case ReversePayment = 14;

    public function label(): string
    {
        return match ($this) {
            self::Regular => 'Звичайна квитанція',
            self::ReAddress => 'Переадресація',
            self::Delivery => 'Доставка',
            self::Insurance => 'Страхування',
            self::CargoPickup => 'Забирання вантажу',
            self::ServiceSale => 'Продаж послуг',
            self::CardTransfer => 'Переказ на карту',
            self::CourierDelivery => "Кур'єрська доставка",
            self::CashOnDelivery => 'Переказ коштів',
            self::ReversePayment => 'Зворотній платіж',
        };
    }
}
