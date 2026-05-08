<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\Enums;

/**
 * Cash-on-delivery transfer type (PDF v3.5.1 §6.4 — cashOnDeliveryType).
 */
enum CashOnDeliveryType: int
{
    case Card = 0;
    case Invoice = 1;
    case Cash = 2;
}
