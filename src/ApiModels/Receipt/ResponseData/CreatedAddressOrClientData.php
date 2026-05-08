<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Receipt\ResponseData;

use Spatie\LaravelData\Data;

/**
 * §6.15 PostCreateAddressOrClient response — wraps both `address` and `account`
 * sub-objects. Either one or both will be populated depending on which model
 * type the request submitted.
 */
final class CreatedAddressOrClientData extends Data
{
    public function __construct(
        public ?CreatedAddressData $address = null,
        public ?CreatedClientAccountData $account = null,
    ) {}
}
