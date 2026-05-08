<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Receipt\ResponseData;

use Spatie\LaravelData\Data;

final class CreatedAddressData extends Data
{
    public function __construct(
        public int $Id,
        public ?string $Street = null,
        public ?string $House = null,
        public ?string $Appartament = null,
        public ?string $AccountId = null,
        public ?string $CityId = null,
        public ?string $Territoria = null,
        public ?int $StateCode = null,
        public ?string $EntityId = null,
        public ?string $Index = null,
    ) {}
}
