<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Calculation;

use Sashalenz\DeliveryAuto\ApiModels\BaseModel;
use Sashalenz\DeliveryAuto\ApiModels\Calculation\RequestData\GetCargoCategoryRequest;
use Sashalenz\DeliveryAuto\ApiModels\Calculation\RequestData\GetDeliverySchemeRequest;
use Sashalenz\DeliveryAuto\ApiModels\Calculation\RequestData\GetDopUslugiClassificationRequest;
use Sashalenz\DeliveryAuto\ApiModels\Calculation\RequestData\GetInsuranceCostRequest;
use Sashalenz\DeliveryAuto\ApiModels\Calculation\RequestData\GetTariffCategoryRequest;
use Sashalenz\DeliveryAuto\ApiModels\Calculation\RequestData\PostReceiptCalculateRequest;
use Sashalenz\DeliveryAuto\ApiModels\Calculation\ResponseData\CalculationData;
use Sashalenz\DeliveryAuto\ApiModels\Calculation\ResponseData\CargoCategoryData;
use Sashalenz\DeliveryAuto\ApiModels\Calculation\ResponseData\DeliverySchemeData;
use Sashalenz\DeliveryAuto\ApiModels\Calculation\ResponseData\DopUslugaClassificationData;
use Sashalenz\DeliveryAuto\ApiModels\Calculation\ResponseData\InsuranceCostData;
use Sashalenz\DeliveryAuto\ApiModels\Calculation\ResponseData\TariffCategoryData;
use Sashalenz\DeliveryAuto\Exceptions\DeliveryAutoException;
use Sashalenz\DeliveryAuto\Request;
use Spatie\LaravelData\DataCollection;

/**
 * §3 Розрахунок вартості перевезення — public calculator endpoints.
 */
final class Calculation extends BaseModel
{
    /**
     * @return DataCollection<int, DopUslugaClassificationData>
     *
     * @throws DeliveryAutoException
     */
    public function getDopUslugiClassification(GetDopUslugiClassificationRequest $request): DataCollection
    {
        return $this
            ->reset()
            ->method('GetDopUslugiClassification')
            ->params($request)
            ->toCollection(DopUslugaClassificationData::class);
    }

    /**
     * @return DataCollection<int, TariffCategoryData>
     *
     * @throws DeliveryAutoException
     */
    public function getTariffCategory(GetTariffCategoryRequest $request): DataCollection
    {
        return $this
            ->reset()
            ->method('GetTariffCategory')
            ->params($request)
            ->toCollection(TariffCategoryData::class);
    }

    /**
     * @return DataCollection<int, CargoCategoryData>
     *
     * @throws DeliveryAutoException
     */
    public function getCargoCategory(GetCargoCategoryRequest $request): DataCollection
    {
        return $this
            ->reset()
            ->method('GetCargoCategory')
            ->params($request)
            ->toCollection(CargoCategoryData::class);
    }

    /**
     * @return DataCollection<int, DeliverySchemeData>
     *
     * @throws DeliveryAutoException
     */
    public function getDeliveryScheme(GetDeliverySchemeRequest $request): DataCollection
    {
        return $this
            ->reset()
            ->method('GetDeliveryScheme')
            ->params($request)
            ->toCollection(DeliverySchemeData::class);
    }

    /**
     * @throws DeliveryAutoException
     */
    public function postReceiptCalculate(PostReceiptCalculateRequest $request): ?CalculationData
    {
        /** @var CalculationData|null */
        return $this
            ->reset()
            ->method('PostReceiptCalculate')
            ->params($request)
            ->post()
            ->toData(CalculationData::class);
    }

    /**
     * §3.7 Insurance cost.
     *
     * Response shape is unusual — `Value` / `MinValue` sit at the top level
     * alongside `status` / `message`, not under a `data` key. We use the
     * special root data-key marker to pull the envelope-stripped payload.
     *
     * @throws DeliveryAutoException
     */
    public function getInsuranceCost(GetInsuranceCostRequest $request): ?InsuranceCostData
    {
        $payload = $this
            ->reset()
            ->method('GetInsuranceCost')
            ->params($request)
            ->dataKey(Request::DATA_KEY_ROOT)
            ->get();

        if ($payload->isEmpty()) {
            return null;
        }

        return InsuranceCostData::from($payload->all());
    }
}
