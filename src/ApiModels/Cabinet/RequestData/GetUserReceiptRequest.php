<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Cabinet\RequestData;

use Sashalenz\DeliveryAuto\Enums\Culture;
use Sashalenz\DeliveryAuto\Enums\ReceiptListType;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;

final class GetUserReceiptRequest extends Data
{
    public function __construct(
        #[Min(1)]
        public int $page = 1,
        #[Min(1)]
        public int $rows = 10,
        public ReceiptListType $type = ReceiptListType::Outgoing,
        public ?Culture $culture = null,
        public bool $detail = true,
    ) {}
}
