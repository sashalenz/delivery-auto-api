<?php

namespace Sashalenz\DeliveryAuto\ApiModels;

use Illuminate\Support\Collection;
use Sashalenz\DeliveryAuto\DataTransferObjects\Warehouse\AreaDataTransferObject;
use Sashalenz\DeliveryAuto\DataTransferObjects\Warehouse\RegionDataTransferObject;
use Sashalenz\DeliveryAuto\DataTransferObjects\Warehouse\WarehouseDataTransferObject;
use Sashalenz\DeliveryAuto\DataTransferObjects\Warehouse\WarehouseFindDataTransferObject;
use Sashalenz\DeliveryAuto\DataTransferObjects\Warehouse\WarehouseInfoDataTransferObject;
use Sashalenz\DeliveryAuto\Exceptions\DeliveryException;

final class Warehouse extends BaseModel
{
    /**
     * @return Collection
     * @throws DeliveryException
     */
    public function getRegionList(): Collection
    {
        return $this->method('GetRegionList')
            ->validate([
                'country' => ['required', 'numeric', 'in:1,2'],
            ])
            ->request()
            ->map(fn (array $array) => RegionDataTransferObject::fromArray($array));
    }

    /**
     * @return Collection
     * @throws DeliveryException
     */
    public function getAreasList(): Collection
    {
        return $this->method('getAreasList')
            ->validate([
                'regionId' => ['nullable', 'uuid'],
                'country' => ['nullable', 'numeric', 'in:1,2'],
                'fl_all' => ['nullable', 'boolean'],
            ])
            ->request()
            ->map(fn (array $array) => AreaDataTransferObject::fromArray($array));
    }

    /**
     * @return Collection
     * @throws DeliveryException
     */
    public function getWarehousesList(): Collection
    {
        return $this->method('GetWarehousesList')
            ->validate([
                'includeRegionalCenters' => ['nullable', 'bool'],
                'CityId' => ['nullable', 'uuid'],
                'RegionId' => ['nullable', 'uuid'],
                'country' => ['nullable', 'numeric', 'in:1,2'],
            ])
            ->request()
            ->map(fn (array $array) => WarehouseDataTransferObject::fromArray($array));
    }

    /**
     * @return WarehouseInfoDataTransferObject
     * @throws DeliveryException
     */
    public function getWarehousesInfo(): WarehouseInfoDataTransferObject
    {
        return WarehouseInfoDataTransferObject::fromArray(
            $this->method('GetWarehousesInfo')
            ->validate([
                'WarehousesId' => ['required', 'uuid'],
            ])
            ->request()
            ->toArray()
        );
    }

    /**
     * @return Collection
     * @throws DeliveryException
     */
    public function getFindWarehouses(): Collection
    {
        return $this->method('GetFindWarehouses')
            ->validate([
                'Longitude' => ['nullable', 'numeric'],
                'Latitude' => ['nullable', 'numeric'],
                'count' => ['nullable', 'numeric', 'min:1'],
                'includeRegionalCenters' => ['nullable', 'boolean'],
                'CityId' => ['required', 'uuid'],
            ])
            ->request()
            ->map(fn (array $array) => WarehouseFindDataTransferObject::fromArray($array));
    }

    /**
     * @return Collection
     * @throws DeliveryException
     */
    public function getWarehousesListInDetail(): Collection
    {
        return $this->method('GetWarehousesListInDetail')
            ->validate([
                'country' => ['nullable', 'in:1,2'],
                'onlyWarehouses' => ['nullable', 'boolean'],
                'CityId' => ['nullable', 'uuid'],
            ])
            ->request()
            ->map(fn (array $array) => WarehouseInfoDataTransferObject::fromArray($array));
    }
}
