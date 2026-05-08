<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Calculation\ResponseData;

use Spatie\LaravelData\Data;

final class CategoryCalculationData extends Data
{
    public function __construct(
        public string $categoryId,
        public ?string $categoryIdName = null,
        public ?string $cargoCategoryId = null,
        public ?string $cargoCategoryIdName = null,
        public ?int $classification = null,
        public ?int $countPlace = null,
        public ?float $helf = null,
        public ?float $size = null,
        public ?float $height = null,
        public ?float $lenght = null,
        public ?float $width = null,
        public ?float $helfTarif = null,
        public ?float $egTarif = null,
        public ?float $oformlenie = null,
        public ?float $oformlenieCost = null,
        public ?float $deliveryCost = null,
        public ?float $documentCost = null,
        public ?string $comment = null,
        public ?bool $isEconom = null,
        public ?bool $isExpress = null,
        public ?bool $isIndividual = null,
        public ?string $PartnerNumber = null,
        public ?float $weightSummary = null,
        public ?float $volumeSummary = null,
    ) {}
}
