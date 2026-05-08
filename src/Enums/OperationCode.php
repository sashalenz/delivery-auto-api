<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\Enums;

/**
 * Operation log codes returned by GetUnidersalLogsByReceiptNumber (PDF v3.5.1 §8.3).
 *
 * Note: only known codes are enumerated. Unknown codes returned by the API
 * should fall back to integer comparison; the mapping in Delivery-Auto evolves
 * over time, so consumers should treat this enum as best-effort labelling.
 */
enum OperationCode: int
{
    case ReceiptIssuedAtWarehouse = 100000002;
    case ArrivalDateChanged = 100000013;
    case ReceiptReAddressedBetweenSameCityWarehouses = 100000016;
    case CargoLoadedToVehicle1 = 100000018;
    case ReceiptDeliveryDateChanged = 100000026;
    case CargoLoadedToVehicle2 = 100000059;
    case CargoUnloadedFromVehicle1 = 100000060;
    case CargoUnloadedFromVehicle2 = 100000061;
    case CargoLoadedToVehicle3 = 100000062;
    case ReceiptIssuanceCancelled1 = 100000070;
    case CargoIssuedToClient1 = 100000072;
    case CargoLoadedToVehicle4 = 100000079;
    case ReceiptReAddressedBetweenCities = 100000082;
    case ReceiptIssuedAtWarehouse2 = 100000111;
    case ReverseReceiptIssuedAtWarehouse = 100000115;
    case ReceiptIssuanceCancelled2 = 100000122;
    case CargoIssuedToClient2 = 100000125;
    case CargoUnloadedFromVehicle3 = 100000132;

    public function label(): string
    {
        return match ($this) {
            self::ReceiptIssuedAtWarehouse, self::ReceiptIssuedAtWarehouse2 => 'Оформлення квитанції на складі',
            self::ArrivalDateChanged => 'Зміна дати прибуття квитанції',
            self::ReceiptReAddressedBetweenSameCityWarehouses => 'Переадресація квитанції між складами одного міста',
            self::CargoLoadedToVehicle1, self::CargoLoadedToVehicle2,
            self::CargoLoadedToVehicle3, self::CargoLoadedToVehicle4 => 'Завантаження вантажу в автомобіль',
            self::ReceiptDeliveryDateChanged => 'Зміна дати отримання квитанції при доставці',
            self::CargoUnloadedFromVehicle1, self::CargoUnloadedFromVehicle2 => 'Розвантаження вантажу з машини',
            self::CargoUnloadedFromVehicle3 => 'Вивантаження вантажу з машини',
            self::ReceiptIssuanceCancelled1, self::ReceiptIssuanceCancelled2 => 'Скасування видачі квитанції',
            self::CargoIssuedToClient1, self::CargoIssuedToClient2 => 'Видача вантажу клієнту',
            self::ReceiptReAddressedBetweenCities => 'Переадресація квитанції між містами',
            self::ReverseReceiptIssuedAtWarehouse => 'Оформлення зворотної квитанції на складі',
        };
    }
}
