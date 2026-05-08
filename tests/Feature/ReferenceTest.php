<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use Sashalenz\DeliveryAuto\ApiModels\Reference\RequestData\GetAreasListRequest;
use Sashalenz\DeliveryAuto\ApiModels\Reference\RequestData\GetFindWarehousesRequest;
use Sashalenz\DeliveryAuto\ApiModels\Reference\RequestData\GetRegionListRequest;
use Sashalenz\DeliveryAuto\ApiModels\Reference\RequestData\GetWarehousesInfoRequest;
use Sashalenz\DeliveryAuto\ApiModels\Reference\RequestData\GetWarehousesListByCityRequest;
use Sashalenz\DeliveryAuto\ApiModels\Reference\ResponseData\AreaData;
use Sashalenz\DeliveryAuto\ApiModels\Reference\ResponseData\RegionData;
use Sashalenz\DeliveryAuto\ApiModels\Reference\ResponseData\WarehouseFindData;
use Sashalenz\DeliveryAuto\ApiModels\Reference\ResponseData\WarehouseInfoData;
use Sashalenz\DeliveryAuto\DeliveryAuto;
use Sashalenz\DeliveryAuto\Enums\Country;
use Sashalenz\DeliveryAuto\Enums\Culture;
use Sashalenz\DeliveryAuto\Enums\DirectionType;

it('parses GetRegionList response into RegionData collection', function () {
    Http::fake([
        '*GetRegionList*' => Http::response([
            'status' => true,
            'message' => '',
            'data' => [
                ['id' => -1, 'name' => 'Усі', 'externalId' => '00000000-0000-0000-0000-000000000000'],
                ['id' => 3898, 'name' => 'Вінницька область', 'externalId' => 'c8ad84fe-cf49-e211-9515-00155d012d0d'],
            ],
        ]),
    ]);

    $regions = DeliveryAuto::reference()->getRegionList(new GetRegionListRequest(
        culture: Culture::UkUA,
        country: Country::UA,
    ));

    expect($regions)->toHaveCount(2);
    /** @var RegionData $first */
    $first = $regions[0];
    expect($first)->toBeInstanceOf(RegionData::class)
        ->and($first->id)->toBe(-1)
        ->and($first->name)->toBe('Усі');
});

it('passes cityName autocomplete parameter to GetAreasList', function () {
    Http::fake([
        '*GetAreasList*' => Http::response([
            'status' => true,
            'message' => '',
            'data' => [[
                'id' => '08f54093-d12a-e311-8b0d-00155d037960',
                'name' => 'Авангард',
                'RegionId' => 'e4ad84fe-cf49-e211-9515-00155d012d0d',
                'IsWarehouse' => true,
                'ExtracityPickup' => false,
                'ExtracityShipping' => false,
                'RAP' => false,
                'RAS' => false,
                'regionName' => 'Одеська область',
                'regionId' => 3911,
                'country' => 1,
                'districtName' => 'Овідіопольський',
            ]],
        ]),
    ]);

    $areas = DeliveryAuto::reference()->getAreasList(new GetAreasListRequest(
        cityName: 'Авангард',
    ));

    Http::assertSent(fn ($req) => str_contains($req->url(), 'cityName=%D0%90%D0%B2%D0%B0%D0%BD%D0%B3%D0%B0%D1%80%D0%B4'));
    expect($areas)->toHaveCount(1);
    /** @var AreaData $area */
    $area = $areas[0];
    expect($area->name)->toBe('Авангард')
        ->and($area->IsWarehouse)->toBeTrue();
});

it('returns null when GetWarehousesInfo response is empty', function () {
    Http::fake([
        '*GetWarehousesInfo*' => Http::response([
            'status' => true,
            'message' => '',
            'data' => null,
        ]),
    ]);

    $info = DeliveryAuto::reference()->getWarehousesInfo(new GetWarehousesInfoRequest(
        WarehousesId: 'e627c8fd-d549-e211-9515-00155d012d0d',
    ));

    expect($info)->toBeNull();
});

it('parses GetWarehousesListByCity into WarehouseInfoData collection', function () {
    Http::fake([
        '*GetWarehousesListByCity*' => Http::response([
            'status' => true,
            'message' => '',
            'data' => [
                [
                    'id' => 'e627c8fd-d549-e211-9515-00155d012d0d',
                    'name' => 'ОЛЕКСАНДРІЯ',
                    'address' => 'вул. Діброви, 16',
                    'operatingTime' => 'ПН-ПТ: 9:00-18:00',
                    'IsWarehouse' => true,
                    'IsCashOnDelivery' => true,
                    'WarehouseType' => 3,
                    'CenterPickUpDelivery' => false,
                    'Number' => 0,
                ],
            ],
        ]),
    ]);

    $warehouses = DeliveryAuto::reference()->getWarehousesListByCity(new GetWarehousesListByCityRequest(
        CityId: '1e8e7257-a82a-e311-8b0d-00155d037960',
        DirectionType: DirectionType::Send,
    ));

    expect($warehouses)->toHaveCount(1);
    /** @var WarehouseInfoData $w */
    $w = $warehouses[0];
    expect($w)->toBeInstanceOf(WarehouseInfoData::class)
        ->and($w->name)->toBe('ОЛЕКСАНДРІЯ');
});

it('passes coordinates and Type filter to GetFindWarehouses', function () {
    Http::fake([
        '*GetFindWarehouses*' => Http::response([
            'status' => true,
            'message' => '',
            'data' => [
                [
                    'id' => '11fb447a-4a97-e411-bf7a-000d3a200160',
                    'name' => 'КУРАХОВЕ',
                    'distance' => 20.7,
                    'IsWarehouse' => true,
                    'Number' => 0,
                ],
            ],
        ]),
    ]);

    $found = DeliveryAuto::reference()->getFindWarehouses(new GetFindWarehousesRequest(
        Longitude: 37.27,
        Latitude: 47.99,
        count: 5,
    ));

    expect($found)->toHaveCount(1);
    /** @var WarehouseFindData $w */
    $w = $found[0];
    expect($w->distance)->toBe(20.7);
});

it('caches a getRegionList call across two invocations', function () {
    Http::fake([
        '*GetRegionList*' => Http::response([
            'status' => true,
            'data' => [['id' => 1, 'name' => 'X', 'externalId' => 'x']],
        ]),
    ]);

    DeliveryAuto::reference()->cache(60)->getRegionList(new GetRegionListRequest(country: Country::UA));
    DeliveryAuto::reference()->cache(60)->getRegionList(new GetRegionListRequest(country: Country::UA));

    Http::assertSentCount(1);
});
