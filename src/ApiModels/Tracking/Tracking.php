<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Tracking;

use Sashalenz\DeliveryAuto\ApiModels\BaseModel;
use Sashalenz\DeliveryAuto\ApiModels\Tracking\RequestData\GetDateArrivalRequest;
use Sashalenz\DeliveryAuto\ApiModels\Tracking\RequestData\GetReceiptDetailsRequest;
use Sashalenz\DeliveryAuto\ApiModels\Tracking\RequestData\GetStickersRequest;
use Sashalenz\DeliveryAuto\ApiModels\Tracking\ResponseData\DateArrivalData;
use Sashalenz\DeliveryAuto\ApiModels\Tracking\ResponseData\ReceiptData;
use Sashalenz\DeliveryAuto\ApiModels\Tracking\ResponseData\StickerData;
use Sashalenz\DeliveryAuto\Exceptions\DeliveryAutoException;
use Spatie\LaravelData\DataCollection;

/**
 * §2 Квитанції — TTN tracking. Public methods (no auth) plus the auth-protected
 * GetStickers (§6.16) which also lives here because it's read-only sticker data.
 */
final class Tracking extends BaseModel
{
    /**
     * @throws DeliveryAutoException
     */
    public function getReceiptDetails(GetReceiptDetailsRequest $request): ?ReceiptData
    {
        /** @var ReceiptData|null */
        return $this
            ->reset()
            ->method('GetReceiptDetails')
            ->params($request)
            ->toData(ReceiptData::class);
    }

    /**
     * @throws DeliveryAutoException
     */
    public function getDateArrival(GetDateArrivalRequest $request): ?DateArrivalData
    {
        /** @var DateArrivalData|null */
        return $this
            ->reset()
            ->method('GetDateArrival')
            ->params($request)
            ->toData(DateArrivalData::class);
    }

    /**
     * @return DataCollection<int, StickerData>
     *
     * @throws DeliveryAutoException
     */
    public function getStickers(GetStickersRequest $request): DataCollection
    {
        return $this
            ->reset()
            ->method('GetStickers')
            ->params($request)
            ->withHmac()
            ->toCollection(StickerData::class);
    }
}
