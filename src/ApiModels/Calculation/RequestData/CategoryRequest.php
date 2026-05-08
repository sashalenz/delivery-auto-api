<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Calculation\RequestData;

use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Uuid;
use Spatie\LaravelData\Data;

/**
 * Cargo category line for PostReceiptCalculate / PostCreateReceipts.
 */
final class CategoryRequest extends Data
{
    public function __construct(
        #[Required, Uuid]
        public string $categoryId,
        #[Required, Min(1)]
        public int $countPlace,
        public ?float $helf = null,
        public ?float $size = null,
        public ?int $height = null,
        public ?int $lenght = null,
        public ?int $width = null,
        #[Uuid]
        public ?string $cargoCategoryId = null,
        public ?bool $isEconom = null,
        public ?string $PartnerNumber = null,
    ) {}
}
