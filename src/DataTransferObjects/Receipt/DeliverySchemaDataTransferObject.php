<?php

namespace Sashalenz\DeliveryAuto\DataTransferObjects\Receipt;

use Sashalenz\DeliveryAuto\DataTransferObjects\DeliveryDataTransferObject;

class DeliverySchemaDataTransferObject extends DeliveryDataTransferObject
{
    public int $id;
    public string $name;

    public static function fromArray(array $array): self
    {
        return new self([
            'id' => (int) $array['id'],
            'name' => $array['name'],
        ]);
    }
}
