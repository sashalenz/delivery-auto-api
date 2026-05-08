<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Receipt\RequestData;

use Spatie\LaravelData\Attributes\Validation\Uuid;
use Spatie\LaravelData\Data;

/**
 * §6.15 PostCreateAddressOrClient — accepts either ClientModel or AddressModel.
 *
 * - To CREATE a client: pass `ClientType`, `Name`, `FirstName`/`LastName`/`SecondName`,
 *   `CityId`, `Egrpo`, `PhoneNumber`, `Street`/`House`/`Appartament`, `senderId`.
 *
 * - To CREATE an address for an existing client: pass `AccountId`, `CityId`,
 *   `Street`, `House`, `Appartament`, `senderId`.
 *
 * The endpoint dispatches based on which fields are populated.
 */
final class PostCreateAddressOrClientRequest extends Data
{
    public function __construct(
        public ?string $AccountId = null,
        public ?bool $ClientType = null,
        public ?string $Name = null,
        public ?string $SecondName = null,
        public ?string $FirstName = null,
        public ?string $LastName = null,
        #[Uuid]
        public ?string $CityId = null,
        public ?string $Egrpo = null,
        public ?string $PhoneNumber = null,
        public ?string $Street = null,
        public ?string $House = null,
        public ?string $Appartament = null,
        #[Uuid]
        public ?string $senderId = null,
    ) {}
}
