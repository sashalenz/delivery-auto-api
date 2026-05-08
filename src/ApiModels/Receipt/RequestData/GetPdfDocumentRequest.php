<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Receipt\RequestData;

use Sashalenz\DeliveryAuto\Enums\DocumentType;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

/**
 * §6.6 GetPdfDocument — request a PDF document. Numbers are passed as a
 * semicolon-separated string in the `number` query param; the helper accepts
 * either a list or a string and joins it.
 */
final class GetPdfDocumentRequest extends Data
{
    /**
     * @param  array<int,string>|string  $number
     */
    public function __construct(
        #[Required]
        public array|string $number,
        #[Required]
        public DocumentType $type,
    ) {
        if (is_array($this->number)) {
            $this->number = implode(';', $this->number);
        }
    }
}
