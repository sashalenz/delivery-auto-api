<?php

namespace Sashalenz\DeliveryAuto\DataTransferObjects\Warehouse;

use Sashalenz\DeliveryAuto\DataTransferObjects\DeliveryDataTransferObject;

class WarehouseInfoDataTransferObject extends DeliveryDataTransferObject
{
    public string $id;
    public string $name;
    public float $latitude;
    public float $longitude;
    public string $cityId;
    public string $cityName;
    public string $address;
    public string $operatingTime;
    public string $phone;
    public string $emailStorage;
    public bool $office;
    public bool $isWarehouse;
    public ?string $rcPhoneSecurity = null;
    public ?string $rcPhoneManagers = null;
    public ?string $rcPhone = null;
    public ?string $rcName = null;
    public ?string $warehouseForDeliveryId = null;
    public int $warehouseType;

    public static function fromArray(array $array): self
    {
        return new self([
            'id' => $array['id'],
            'name' => $array['name'],
            'latitude' => $array['latitude'],
            'longitude' => $array['longitude'],
            'cityId' => $array['CityId'],
            'cityName' => $array['CityName'],
            'address' => $array['address'],
            'operatingTime' => $array['operatingTime'],
            'phone' => $array['Phone'],
            'emailStorage' => $array['EmailStorage'],
            'office' => isset($array['Office']) ? (bool)$array['Office'] : false,
            'isWarehouse' => (bool) $array['IsWarehouse'],
            'rcPhoneSecurity' => $array['RcPhoneSecurity'] ?? null,
            'rcPhoneManagers' => $array['RcPhoneManagers'] ?? null,
            'rcPhone' => $array['RcPhone'] ?? null,
            'rcName' => $array['RcName'] ?? null,
            'warehouseForDeliveryId' => $array['WarehouseForDeliveryId'] ?? null,
            'warehouseType' => isset($array['WarehouseType']) ? (int) $array['WarehouseType'] : 0,
        ]);
    }
}
