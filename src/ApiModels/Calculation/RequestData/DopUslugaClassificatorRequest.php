<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Calculation\RequestData;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

final class DopUslugaClassificatorRequest extends Data
{
    public function __construct(
        /** @var DataCollection<int, DopUslugaRequest> */
        #[DataCollectionOf(DopUslugaRequest::class)]
        public DataCollection $dopUsluga,
    ) {}
}
