<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Receipt\RequestData;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

/**
 * §6.5 PostDeactivateEg — deactivate cargo units.
 */
final class PostDeactivateEgRequest extends Data
{
    public function __construct(
        /** @var DataCollection<int, EgItemRequest> */
        #[DataCollectionOf(EgItemRequest::class), Required]
        public DataCollection $egs,
    ) {}
}
