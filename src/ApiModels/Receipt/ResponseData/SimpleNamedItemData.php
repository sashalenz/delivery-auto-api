<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Receipt\ResponseData;

use Spatie\LaravelData\Data;

/**
 * Generic {id, name} response — used by GetCurrency, GetPayer, GetClientAddress,
 * GetPosibleReciver. The id type varies (UUID for client/payer/address; integer
 * for currency code), so it's typed as string and the consumer can cast as needed.
 */
final class SimpleNamedItemData extends Data
{
    public function __construct(
        public ?string $id,
        public ?string $name = null,
    ) {}
}
