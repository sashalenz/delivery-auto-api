<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Logs\ResponseData;

use Carbon\Carbon;
use Sashalenz\DeliveryAuto\Casts\CarbonInterfaceCast;
use Sashalenz\DeliveryAuto\Enums\OperationCode;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;

final class ReceiptLogData extends Data
{
    public function __construct(
        #[WithCast(CarbonInterfaceCast::class, format: ['Y-m-d\TH:i:s.v', 'Y-m-d\TH:i:s'])]
        public ?Carbon $CreatedOn = null,
        public ?string $WarehouseId = null,
        public ?string $WarehouseName = null,
        #[WithCast(EnumCast::class, OperationCode::class)]
        public ?OperationCode $OperationCode = null,
        public ?string $OperationName = null,
        public ?string $Number = null,
    ) {}
}
