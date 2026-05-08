<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Tracking\ResponseData;

use Carbon\Carbon;
use Sashalenz\DeliveryAuto\Casts\CarbonInterfaceCast;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

/**
 * §6.16 GetStickers — sticker / barcode metadata for a TTN.
 */
final class StickerData extends Data
{
    public function __construct(
        public string $barcode,
        public ?string $categoryName = null,
        public ?string $receiptNumber = null,
        public ?string $receiver = null,
        #[WithCast(CarbonInterfaceCast::class, format: ['Y-m-d\TH:i:s', 'Y-m-d\TH:i:sP'])]
        public ?Carbon $dateSend = null,
        #[WithCast(CarbonInterfaceCast::class, format: ['Y-m-d\TH:i:s', 'Y-m-d\TH:i:sP'])]
        public ?Carbon $dateReceive = null,
        public ?string $warehouseSend = null,
        public ?string $warehouseReceive = null,
        public ?string $totalPlaces = null,
        public ?int $rang = null,
        public ?bool $econom = null,
        public ?bool $delivery = null,
        public ?bool $postomat = null,
    ) {}
}
