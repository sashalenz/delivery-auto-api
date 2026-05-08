<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Receipt\RequestData;

use Sashalenz\DeliveryAuto\Enums\Culture;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

/**
 * §6.4 PostCreateReceipts — issue one or more TTNs.
 *
 * `flSave` controls whether the API persists the receipt (false → dry-run,
 * vendor calls this "validation mode"). `debugMode` enables verbose error
 * payload — useful while wiring up an integration.
 */
final class PostCreateReceiptsRequest extends Data
{
    public function __construct(
        /** @var DataCollection<int, RegistrationReceiptRequest> */
        #[DataCollectionOf(RegistrationReceiptRequest::class), Required]
        public DataCollection $receiptsList,
        public ?Culture $culture = null,
        public bool $flSave = true,
        public bool $debugMode = false,
    ) {}
}
