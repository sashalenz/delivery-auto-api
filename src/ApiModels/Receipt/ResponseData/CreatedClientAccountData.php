<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Receipt\ResponseData;

use Spatie\LaravelData\Data;

final class CreatedClientAccountData extends Data
{
    public function __construct(
        public int $Id,
        public ?string $AccountId = null,
        public ?bool $ClientType = null,
        public ?string $Name = null,
        public ?string $FirstName = null,
        public ?string $LastName = null,
        public ?string $SecondName = null,
        public ?bool $PaymentType = null,
        public ?string $CityId = null,
        public ?string $Egrpo = null,
        public ?string $Inn = null,
        public ?string $Kpp = null,
        public ?int $OwnershipCode = null,
        public ?string $PhoneNumber = null,
        public ?string $SmsPhoneNumber = null,
        public ?string $ParentAccountId = null,
        public ?string $ParentAccountName = null,
        public ?int $StateCode = null,
        public ?string $CountryCode = null,
        public ?string $MasterId = null,
    ) {}
}
