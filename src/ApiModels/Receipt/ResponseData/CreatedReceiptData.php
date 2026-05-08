<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Receipt\ResponseData;

use Carbon\Carbon;
use Sashalenz\DeliveryAuto\Casts\CarbonInterfaceCast;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

/**
 * §6.4 PostCreateReceipts response item.
 */
final class CreatedReceiptData extends Data
{
    public function __construct(
        public string $Id,
        public string $Number,
        public ?float $TotallCost = null,
        public ?float $InsuranceCost = null,
        public ?float $ComissionGM = null,
        #[WithCast(CarbonInterfaceCast::class, format: ['d.m.Y H:i:s', 'Y-m-d\TH:i:s'])]
        public ?Carbon $ReceiveDate = null,
        public ?string $Comment = null,
        /** @var DataCollection<int, CreatedEgData>|null */
        #[DataCollectionOf(CreatedEgData::class)]
        public ?DataCollection $egs = null,
    ) {}
}
