<?php

namespace Sashalenz\DeliveryAuto\DataTransferObjects;

use Sashalenz\DeliveryAuto\DataTransferObjects\DeliveryDataTransferObject;

class BaseDataTransferObject extends DeliveryDataTransferObject
{
    public string $id;
    public string $name;

    public static function fromArray(array $array): self
    {
        return new self([
            'id' => $array['id'],
            'name' => $array['name']
        ]);
    }
}
