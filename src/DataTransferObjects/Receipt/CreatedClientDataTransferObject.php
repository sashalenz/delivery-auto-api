<?php

namespace Sashalenz\DeliveryAuto\DataTransferObjects\Receipt;

use Sashalenz\DeliveryAuto\DataTransferObjects\DeliveryDataTransferObject;

class CreatedClientDataTransferObject extends DeliveryDataTransferObject
{
    public int $id;
    public string $accountId;
    public bool $clientType;
    public string $name;
    public string $firstName;
    public string $lastName;
    public string $secondName;
    public bool $paymentType;
    public string $cityId;
    public ?string $egrpo;
    public ?string $inn;
    public ?string $kpp;
    public int $ownershipCode;
    public string $phoneNumber;
    public string $smsPhoneNumber;
    public ?string $parentAccountId;
    public ?string $parentAccountName;
    public bool $stateCode;
    public string $countryCode;
    public ?string $masterId;

    public static function fromArray(array $array): self
    {
//        "Id": 278147,
// "AccountId": "1541a45b-1a56-e511-89e5-000d3a200160",
// "ClientType": false,
// "Name": "!! Тестовый Клиент Для сайта 102",
// "FirstName": "Клиент",
// "LastName": "Для сайта 102",
// "SecondName": "!! Тестовый",
// "PaymentType": true,
// "CityId": "16617df3-a42a-e311-8b0d-00155d037960",
// "Egrpo": "",
// "Inn": "",
// "Kpp": "",
// "OwnershipCode": 100000066,
// "PhoneNumber": "0509996665",
// "SmsPhoneNumber": "0509996665",
// "ParentAccountId": null,
// "ParentAccountName": "",
// "StateCode": 0,
// "CountryCode": "38",
// "MasterId": null
        return new self([
            'id' => (int)$array['Id'],
            'accountId' => $array['AccountId'],
            'clientType' => (bool)$array['ClientType'],
            'name' => $array['Name'],
            'firstName' => $array['FirstName'],
            'lastName' => $array['LastName'],
            'secondName' => $array['SecondName'],
            'paymentType' => (bool)$array['PaymentType'],
            'cityId' => $array['CityId'],
            'egrpo' => $array['Egrpo'],
            'inn' => $array['Inn'],
            'kpp' => $array['Kpp'],
            'ownershipCode' => (int)$array['OwnershipCode'],
            'phoneNumber' => $array['PhoneNumber'],
            'smsPhoneNumber' => $array['SmsPhoneNumber'],
            'parentAccountId' => $array['ParentAccountId'],
            'parentAccountName' => $array['ParentAccountName'],
            'stateCode' => (bool)$array['StateCode'],
            'countryCode' => $array['CountryCode'],
            'masterId' => $array['MasterId'],
        ]);
    }
}
