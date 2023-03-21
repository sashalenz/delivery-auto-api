<?php

namespace Sashalenz\DeliveryAuto;

use Sashalenz\DeliveryAuto\ApiModels\Receipt;
use Sashalenz\DeliveryAuto\ApiModels\Warehouse;

final class Delivery
{
    public const CURRENCY = 100000000;

    public static function warehouse(): Warehouse
    {
        return new Warehouse();
    }

    public static function receipt(): Receipt
    {
        return new Receipt();
    }
}
