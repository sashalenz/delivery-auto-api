<?php

namespace Sashalenz\DeliveryAuto\DataTransferObjects\Warehouse;

use Sashalenz\DeliveryAuto\DataTransferObjects\DeliveryDataTransferObject;

class WarehouseFindDataTransferObject extends DeliveryDataTransferObject
{
    public string $id;
    public string $name;
    public float $distance;
    public float $latitude;
    public float $longitude;
    public string $cityName;
    public string $address;
    public bool $isWarehouse;
    public string $phone;
    public string $workingTime;
    public int $warehouseType;
    public bool $isRegionalCentre;
    public bool $isCashOnDelivery;

    public static function fromArray(array $array): self
    {
        return new self([
            'id' => $array['id'],
            'name' => $array['name'],
            'distance' => $array['distance'],
            'latitude' => $array['latitude'],
            'longitude' => $array['longitude'],
            'cityName' => $array['CityName'],
            'address' => $array['address'],
            'isWarehouse' => (bool) $array['IsWarehouse'],
            'cityId' => $array['CityId'],
            'phone' => $array['phone'],
            'workingTime' => $array['working_time'],
            'warehouseType' => (int) $array['WarehouseType'],
            'isRegionalCentre' => (bool) $array['IsRegionalCentre'],
            'isCashOnDelivery' => (bool) $array['IsCashOnDelivery'],
        ]);
    }
}
