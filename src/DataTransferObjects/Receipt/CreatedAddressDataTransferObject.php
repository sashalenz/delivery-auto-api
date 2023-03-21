<?php

namespace Sashalenz\DeliveryAuto\DataTransferObjects\Receipt;

use Sashalenz\DeliveryAuto\DataTransferObjects\DeliveryDataTransferObject;

class CreatedAddressDataTransferObject extends DeliveryDataTransferObject
{
    public int $id;
    public string $street;
    public string $house;
    public string $appartament;
    public string $accountId;
    public string $cityId;
    public ?string $territori = null;
    public bool $stateCode;
    public string $entityId;
    public ?string $index = null;

    public static function fromArray(array $array): self
    {
        return new self([
            'id' => $array['Id'],
            'street' => $array['Street'],
            'house' => $array['House'],
            'appartament' => $array['Appartament'],
            'accountId' => $array['AccountId'],
            'cityId' => $array['CityId'],
            'territoria' => $array['Territoria'],
            'stateCode' => (bool)$array['StateCode'],
            'entityId' => $array['EntityId'],
            'index' => $array['Index'],
        ]);
    }
}
