<?php

namespace Sashalenz\DeliveryAuto\DataTransferObjects\Receipt;

use Illuminate\Support\Collection;
use Sashalenz\DeliveryAuto\DataTransferObjects\DeliveryDataTransferObject;

class DopUslugaClassificationDataTransferObject extends DeliveryDataTransferObject
{
    public int $classification;
    public string $name;
    public Collection $dopUsluga;

    public static function fromArray(array $array): self
    {
        return new self([
            'classification' => (int) $array['classification'],
            'name' => $array['name'],
            'dopUsluga' => DopUslugaDataTransferObject::collectFromArray($array['dopUsluga']),
        ]);
    }
}
