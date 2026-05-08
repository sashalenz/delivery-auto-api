<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Communication;

use Sashalenz\DeliveryAuto\ApiModels\BaseModel;
use Sashalenz\DeliveryAuto\ApiModels\Communication\RequestData\GetMessagesThemeRequest;
use Sashalenz\DeliveryAuto\ApiModels\Communication\RequestData\GetNewsRequest;
use Sashalenz\DeliveryAuto\ApiModels\Communication\RequestData\PostPickUpCargoRequest;
use Sashalenz\DeliveryAuto\ApiModels\Communication\RequestData\PostServiceRateRequest;
use Sashalenz\DeliveryAuto\ApiModels\Communication\ResponseData\MessageThemeData;
use Sashalenz\DeliveryAuto\ApiModels\Communication\ResponseData\NewsItemData;
use Sashalenz\DeliveryAuto\Exceptions\DeliveryAutoException;
use Spatie\LaravelData\DataCollection;

/**
 * §4 Зв'язок із користувачем — public communication endpoints.
 */
final class Communication extends BaseModel
{
    /**
     * @return DataCollection<int, NewsItemData>
     *
     * @throws DeliveryAutoException
     */
    public function getNews(GetNewsRequest $request): DataCollection
    {
        return $this
            ->reset()
            ->method('GetNews')
            ->params($request)
            ->toCollection(NewsItemData::class);
    }

    /**
     * @return DataCollection<int, MessageThemeData>
     *
     * @throws DeliveryAutoException
     */
    public function getMessagesTheme(GetMessagesThemeRequest $request): DataCollection
    {
        return $this
            ->reset()
            ->method('GetMessagesTheme')
            ->params($request)
            ->toCollection(MessageThemeData::class);
    }

    /**
     * Submit a service rating. The endpoint replies with `{status, message}`
     * and no payload, so we only signal success/failure via exceptions.
     *
     * @throws DeliveryAutoException
     */
    public function postServiceRate(PostServiceRateRequest $request): bool
    {
        $this
            ->reset()
            ->method('PostServiceRate')
            ->params($request)
            ->post()
            ->get();

        return true;
    }

    /**
     * Order a pickup vehicle (no auth). Responds with success/failure only.
     *
     * @throws DeliveryAutoException
     */
    public function postPickUpCargo(PostPickUpCargoRequest $request): bool
    {
        $this
            ->reset()
            ->method('PostPickUpCargo')
            ->params($request)
            ->post()
            ->get();

        return true;
    }
}
