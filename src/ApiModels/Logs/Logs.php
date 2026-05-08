<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Logs;

use Sashalenz\DeliveryAuto\ApiModels\BaseModel;
use Sashalenz\DeliveryAuto\ApiModels\Logs\RequestData\GetUnidersalLogsByReceiptNumberRequest;
use Sashalenz\DeliveryAuto\ApiModels\Logs\ResponseData\ReceiptLogData;
use Sashalenz\DeliveryAuto\Exceptions\DeliveryAutoException;
use Spatie\LaravelData\DataCollection;

/**
 * §7 — receipt operation logs (login-session auth, response key is
 * `calculatorModel`).
 */
final class Logs extends BaseModel
{
    /**
     * @return DataCollection<int, ReceiptLogData>
     *
     * @throws DeliveryAutoException
     */
    public function getUnidersalLogsByReceiptNumber(GetUnidersalLogsByReceiptNumberRequest $request): DataCollection
    {
        return $this
            ->reset()
            ->method('GetUnidersalLogsByReceiptNumber')
            ->params($request)
            ->withSession()
            ->dataKey('calculatorModel')
            ->toCollection(ReceiptLogData::class);
    }
}
