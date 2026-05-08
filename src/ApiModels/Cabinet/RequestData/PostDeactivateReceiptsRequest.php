<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Cabinet\RequestData;

use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

/**
 * §5.6 PostDeactivateReceipts — cancel reserved (state=8) receipts.
 *
 * Per PDF the param `receiptsGuids` is a comma-separated string of GUIDs;
 * we accept either a list and join it, or pass a string through.
 */
final class PostDeactivateReceiptsRequest extends Data
{
    /**
     * @param  array<int,string>|string  $receiptsGuids
     */
    public function __construct(
        #[Required]
        public array|string $receiptsGuids,
    ) {
        if (is_array($this->receiptsGuids)) {
            $this->receiptsGuids = implode(',', $this->receiptsGuids);
        }
    }
}
