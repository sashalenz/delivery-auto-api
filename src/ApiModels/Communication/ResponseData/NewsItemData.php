<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Communication\ResponseData;

use Carbon\Carbon;
use Sashalenz\DeliveryAuto\Casts\CarbonInterfaceCast;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

final class NewsItemData extends Data
{
    public function __construct(
        public int $NewsItemId,
        public string $Title,
        public ?string $ShortContent = null,
        public ?string $Content = null,
        #[WithCast(CarbonInterfaceCast::class, format: ['Y-m-d\TH:i:s', 'Y-m-d\TH:i:sP'])]
        public ?Carbon $PublishDate = null,
        public ?string $ImageName = null,
        public ?string $ImageUrl = null,
        public ?string $ImageContent = null,
        public ?string $WarehousesId = null,
    ) {}
}
