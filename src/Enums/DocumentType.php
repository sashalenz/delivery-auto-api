<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\Enums;

/**
 * PDF document type for GetPdfDocument (PDF v3.5.1 §6.6).
 */
enum DocumentType: int
{
    case Receipt = 0;
    case StickersPerPage = 1;
    case StickersOneSheet = 2;
    case MultiReceipt95x95 = 4;
}
