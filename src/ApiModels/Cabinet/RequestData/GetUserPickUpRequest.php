<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Cabinet\RequestData;

use DateTimeInterface;
use Sashalenz\DeliveryAuto\Enums\OrderState;
use Sashalenz\DeliveryAuto\Transformers\CarbonInterfaceTransformer;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;

final class GetUserPickUpRequest extends Data
{
    public function __construct(
        #[Min(1)]
        public int $page = 1,
        #[Min(1)]
        public int $rows = 10,
        public ?OrderState $state = null,
        #[WithTransformer(CarbonInterfaceTransformer::class, format: 'd.m.Y')]
        public ?DateTimeInterface $dateFrom = null,
        #[WithTransformer(CarbonInterfaceTransformer::class, format: 'd.m.Y')]
        public ?DateTimeInterface $dateTo = null,
        public ?bool $pickDeliv = null,
    ) {}
}
