<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Tracking\RequestData;

use DateTimeInterface;
use Sashalenz\DeliveryAuto\Enums\Currency;
use Sashalenz\DeliveryAuto\Transformers\CarbonInterfaceTransformer;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Uuid;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;

final class GetDateArrivalRequest extends Data
{
    public function __construct(
        #[Required, Uuid]
        public string $areasSendId,
        #[Required, Uuid]
        public string $areasResiveId,
        #[Required, WithTransformer(CarbonInterfaceTransformer::class, format: 'd.m.Y')]
        public DateTimeInterface $dateSend,
        public Currency $currency = Currency::UAH,
        #[Uuid]
        public ?string $warehouseSendId = null,
        #[Uuid]
        public ?string $warehouseResiveId = null,
    ) {}
}
