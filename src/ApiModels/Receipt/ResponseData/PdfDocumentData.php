<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Receipt\ResponseData;

use Spatie\LaravelData\Data;

/**
 * §6.6 GetPdfDocument response — `file` is base64-encoded PDF binary.
 *
 * The `binary()` helper decodes it to raw PDF bytes for direct write to disk
 * or piping into a Laravel `Response::make($bin, 200, ['Content-Type' => 'application/pdf'])`.
 */
final class PdfDocumentData extends Data
{
    public function __construct(
        public string $file,
    ) {}

    public function binary(): string
    {
        $decoded = base64_decode($this->file, true);

        return $decoded === false ? '' : $decoded;
    }
}
