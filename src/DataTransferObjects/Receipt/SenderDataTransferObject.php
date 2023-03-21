<?php

namespace Sashalenz\DeliveryAuto\DataTransferObjects\Receipt;

use Sashalenz\DeliveryAuto\DataTransferObjects\DeliveryDataTransferObject;

class SenderDataTransferObject extends DeliveryDataTransferObject
{
    public string $id;
    public string $name;
    public string $cityId;
    public string $cityName;

    public static function fromArray(array $array): self
    {
        return new self([
            'id' => $array['id'],
            'name' => $array['name'],
            'cityId' => $array['cityId'],
            'cityName' => $array['cityName']
        ]);
    }
}
