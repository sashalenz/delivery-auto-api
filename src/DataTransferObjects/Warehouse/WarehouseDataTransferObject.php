<?php

namespace Sashalenz\DeliveryAuto\DataTransferObjects\Warehouse;

use Sashalenz\DeliveryAuto\DataTransferObjects\DeliveryDataTransferObject;

class WarehouseDataTransferObject extends DeliveryDataTransferObject
{
    public string $id;
    public string $name;
    public float $latitude;
    public float $longitude;
    public string $cityId;

    public static function fromArray(array $array): self
    {
        return new self([
            'id' => $array['id'],
            'name' => $array['name'],
            'latitude' => $array['Latitude'],
            'longitude' => $array['Longitude'],
            'cityId' => $array['CityId'],
        ]);
    }
}
