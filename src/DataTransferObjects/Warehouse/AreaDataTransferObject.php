<?php

namespace Sashalenz\DeliveryAuto\DataTransferObjects\Warehouse;

use Sashalenz\DeliveryAuto\DataTransferObjects\DeliveryDataTransferObject;

class AreaDataTransferObject extends DeliveryDataTransferObject
{
    public string $id;
    public string $name;
    public string $regionUUID;
    public bool $isWarehouse;
    public bool $extracityPickup;
    public bool $extracityShipping;
    public bool $RAP;
    public bool $RAS;
    public string $regionName;
    public int $country;
    public ?string $districtName = null;

    public static function fromArray(array $array): self
    {
        return new self([
            'id' => $array['id'],
            'name' => $array['name'],
            'regionUUID' => $array['RegionId'],
            'isWarehouse' => $array['IsWarehouse'],
            'extracityPickup' => $array['ExtracityPickup'],
            'extracityShipping' => $array['ExtracityShipping'],
            'RAP' => $array['RAP'],
            'RAS' => $array['RAS'],
            'regionName' => $array['regionName'],
            'regionId' => $array['regionId'],
            'country' => $array['country'],
            'districtName' => $array['districtName'] ?? null,
        ]);
    }
}
