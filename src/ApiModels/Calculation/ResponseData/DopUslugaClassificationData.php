<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Calculation\ResponseData;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

final class DopUslugaClassificationData extends Data
{
    public function __construct(
        public int $classification,
        public string $name,
        /** @var DataCollection<int, DopUslugaData> */
        #[DataCollectionOf(DopUslugaData::class)]
        public DataCollection $dopUsluga,
    ) {}
}
