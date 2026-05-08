<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Reference;

use Sashalenz\DeliveryAuto\ApiModels\BaseModel;
use Sashalenz\DeliveryAuto\ApiModels\Reference\RequestData\GetAreasListRequest;
use Sashalenz\DeliveryAuto\ApiModels\Reference\RequestData\GetFindWarehousesRequest;
use Sashalenz\DeliveryAuto\ApiModels\Reference\RequestData\GetRegionListRequest;
use Sashalenz\DeliveryAuto\ApiModels\Reference\RequestData\GetWarehousesInfoRequest;
use Sashalenz\DeliveryAuto\ApiModels\Reference\RequestData\GetWarehousesListByCityRequest;
use Sashalenz\DeliveryAuto\ApiModels\Reference\RequestData\GetWarehousesListInDetailRequest;
use Sashalenz\DeliveryAuto\ApiModels\Reference\RequestData\GetWarehousesListRequest;
use Sashalenz\DeliveryAuto\ApiModels\Reference\ResponseData\AreaData;
use Sashalenz\DeliveryAuto\ApiModels\Reference\ResponseData\RegionData;
use Sashalenz\DeliveryAuto\ApiModels\Reference\ResponseData\WarehouseData;
use Sashalenz\DeliveryAuto\ApiModels\Reference\ResponseData\WarehouseFindData;
use Sashalenz\DeliveryAuto\ApiModels\Reference\ResponseData\WarehouseInfoData;
use Sashalenz\DeliveryAuto\Exceptions\DeliveryAutoException;
use Spatie\LaravelData\DataCollection;

/**
 * §1 Представництва — public reference data (no auth, cacheable).
 */
final class Reference extends BaseModel
{
    /**
     * @return DataCollection<int, RegionData>
     *
     * @throws DeliveryAutoException
     */
    public function getRegionList(GetRegionListRequest $request): DataCollection
    {
        return $this
            ->reset()
            ->method('GetRegionList')
            ->params($request)
            ->toCollection(RegionData::class);
    }

    /**
     * @return DataCollection<int, AreaData>
     *
     * @throws DeliveryAutoException
     */
    public function getAreasList(GetAreasListRequest $request): DataCollection
    {
        return $this
            ->reset()
            ->method('GetAreasList')
            ->params($request)
            ->toCollection(AreaData::class);
    }

    /**
     * @return DataCollection<int, WarehouseData>
     *
     * @throws DeliveryAutoException
     */
    public function getWarehousesList(GetWarehousesListRequest $request): DataCollection
    {
        return $this
            ->reset()
            ->method('GetWarehousesList')
            ->params($request)
            ->toCollection(WarehouseData::class);
    }

    /**
     * @throws DeliveryAutoException
     */
    public function getWarehousesInfo(GetWarehousesInfoRequest $request): ?WarehouseInfoData
    {
        /** @var WarehouseInfoData|null */
        return $this
            ->reset()
            ->method('GetWarehousesInfo')
            ->params($request)
            ->toData(WarehouseInfoData::class);
    }

    /**
     * @return DataCollection<int, WarehouseInfoData>
     *
     * @throws DeliveryAutoException
     */
    public function getWarehousesListByCity(GetWarehousesListByCityRequest $request): DataCollection
    {
        return $this
            ->reset()
            ->method('GetWarehousesListByCity')
            ->params($request)
            ->toCollection(WarehouseInfoData::class);
    }

    /**
     * @return DataCollection<int, WarehouseFindData>
     *
     * @throws DeliveryAutoException
     */
    public function getFindWarehouses(GetFindWarehousesRequest $request): DataCollection
    {
        return $this
            ->reset()
            ->method('GetFindWarehouses')
            ->params($request)
            ->toCollection(WarehouseFindData::class);
    }

    /**
     * @return DataCollection<int, WarehouseInfoData>
     *
     * @throws DeliveryAutoException
     */
    public function getWarehousesListInDetail(GetWarehousesListInDetailRequest $request): DataCollection
    {
        return $this
            ->reset()
            ->method('GetWarehousesListInDetail')
            ->params($request)
            ->toCollection(WarehouseInfoData::class);
    }
}
