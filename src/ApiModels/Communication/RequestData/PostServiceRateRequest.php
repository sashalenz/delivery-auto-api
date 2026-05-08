<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Communication\RequestData;

use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Uuid;
use Spatie\LaravelData\Data;

/**
 * §4.4 PostServiceRate — submit a rating for a warehouse / company service.
 */
final class PostServiceRateRequest extends Data
{
    public function __construct(
        #[Required, Uuid]
        public string $OfficeId,
        public ?int $WarehosePlacing = null,
        public ?int $CargoReceiveSpeed = null,
        public ?int $CargoOutputSpeed = null,
        public ?int $DocumentsIssuanceSpeed = null,
        public ?int $DeliverySpeed = null,
        public ?int $TarrifsRate = null,
        public ?int $CargoLoadTarrifs = null,
        public ?int $WorkersCulture = null,
        public ?int $QualityInGeneral = null,
        public ?string $YourRecomendations = null,
        public ?string $ClientNumber = null,
        public ?string $Name = null,
        public ?string $LastName = null,
        public ?string $SecondName = null,
        public ?string $Phone = null,
        public ?string $Email = null,
        public ?string $CompanyName = null,
    ) {}
}
