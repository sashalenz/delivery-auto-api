<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Communication\RequestData;

use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

/**
 * §4.5 PostPickUpCargo — order a vehicle to pick up cargo.
 */
final class PostPickUpCargoRequest extends Data
{
    public function __construct(
        #[Required]
        public string $ContactName,
        #[Required]
        public string $Name,
        #[Required]
        public string $PhoneNumber,
        #[Required]
        public string $Email,
        #[Required]
        public string $Area,
        #[Required]
        public string $City,
        #[Required]
        public string $Address,
        #[Required]
        public string $Date,
        public ?string $AccessMode = null,
        public ?int $Weight = null,
        public ?int $Size = null,
        public ?int $Quantity = null,
        public ?string $Time = null,
        public ?string $Note = null,
        public ?bool $IsFloor = null,
        public ?string $Floor = null,
        public ?string $ToCity = null,
    ) {}
}
