<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use Sashalenz\DeliveryAuto\ApiModels\Calculation\RequestData\CategoryRequest;
use Sashalenz\DeliveryAuto\ApiModels\Calculation\RequestData\GetCargoCategoryRequest;
use Sashalenz\DeliveryAuto\ApiModels\Calculation\RequestData\GetDeliverySchemeRequest;
use Sashalenz\DeliveryAuto\ApiModels\Calculation\RequestData\GetInsuranceCostRequest;
use Sashalenz\DeliveryAuto\ApiModels\Calculation\RequestData\PostReceiptCalculateRequest;
use Sashalenz\DeliveryAuto\DeliveryAuto;
use Sashalenz\DeliveryAuto\Enums\DeliveryScheme;
use Spatie\LaravelData\DataCollection;

it('GetCargoCategory does NOT attach HMAC auth (was broken pre-2.0)', function () {
    Http::fake([
        '*GetCargoCategory*' => Http::response([
            'status' => true,
            'data' => [['id' => '0f07d03b-9e36-e311-8b0d-00155d037960', 'name' => 'Документи']],
        ]),
    ]);

    DeliveryAuto::calculation()->getCargoCategory(new GetCargoCategoryRequest(
        TariffCategoryId: '00000000-0000-0000-0000-000000000000',
    ));

    Http::assertSent(fn ($req) => empty($req->header('HMACAuthorization'))
        && str_contains($req->url(), 'TariffCategoryId=00000000-0000-0000-0000-000000000000'));
});

it('parses GetDeliveryScheme response with enum-cast scheme ids', function () {
    Http::fake([
        '*GetDeliveryScheme*' => Http::response([
            'status' => true,
            'data' => [
                ['name' => 'Warehouse-Warehouse', 'id' => 0],
                ['name' => 'Door-Door', 'id' => 1],
                ['name' => 'Warehouse-Door', 'id' => 2],
                ['name' => 'Door-Warehouse', 'id' => 3],
            ],
        ]),
    ]);

    $schemes = DeliveryAuto::calculation()->getDeliveryScheme(new GetDeliverySchemeRequest(
        CitySendId: '4fc948a7-3729-e311-8b0d-00155d037960',
        CityReceiveId: 'e3ac6f68-3529-e311-8b0d-00155d037960',
        WarehouseReceiveId: 'd908c5e1-b36b-e211-81e9-00155d012a15',
    ));

    expect($schemes)->toHaveCount(4)
        ->and($schemes[0]->id)->toBe(DeliveryScheme::WarehouseWarehouse)
        ->and($schemes[1]->id)->toBe(DeliveryScheme::DoorDoor);
});

it('PostReceiptCalculate sends category array as POST body and parses response', function () {
    Http::fake([
        '*PostReceiptCalculate*' => Http::response([
            'status' => true,
            'message' => '',
            'data' => [
                'allSumma' => 4330.5,
                'status' => true,
                'SummaryTransportCost' => 230,
                'SummaryDuCost' => 99,
                'SummaryOformlenieCost' => 1.5,
                'currency' => 100000000,
                'category' => [],
                'dopUslugaClassificator' => [],
            ],
        ]),
    ]);

    $result = DeliveryAuto::calculation()->postReceiptCalculate(new PostReceiptCalculateRequest(
        areasSendId: '4fc948a7-3729-e311-8b0d-00155d037960',
        areasResiveId: 'e3ac6f68-3529-e311-8b0d-00155d037960',
        warehouseSendId: '1c828aa6-70c8-e211-9902-00155d037919',
        warehouseResiveId: 'd908c5e1-b36b-e211-81e9-00155d012a15',
        InsuranceValue: 1000000,
        CashOnDeliveryValue: 5000,
        dateSend: new DateTime('2014-06-06'),
        deliveryScheme: DeliveryScheme::WarehouseDoor,
        category: new DataCollection(CategoryRequest::class, [
            ['categoryId' => '00000000-0000-0000-0000-000000000000', 'countPlace' => 1, 'helf' => 1, 'size' => 1],
        ]),
    ));

    expect($result)->not->toBeNull()
        ->and($result->allSumma)->toBe(4330.5)
        ->and($result->SummaryTransportCost)->toBe(230.0);

    Http::assertSent(function ($req) {
        return $req->method() === 'POST'
            && str_contains($req->url(), 'PostReceiptCalculate')
            && $req['areasSendId'] === '4fc948a7-3729-e311-8b0d-00155d037960'
            && $req['deliveryScheme'] === 2;
    });
});

it('GetInsuranceCost reconstructs the unconventional root-level response', function () {
    Http::fake([
        '*GetInsuranceCost*' => Http::response([
            'status' => true,
            'message' => null,
            'Value' => 40.0,
            'MinValue' => 10000.0,
        ]),
    ]);

    $cost = DeliveryAuto::calculation()->getInsuranceCost(new GetInsuranceCostRequest(
        WarehouseSendId: '00000000-0000-0000-0000-000000000001',
        WarehouseReceiveId: '00000000-0000-0000-0000-000000000002',
        InsuranceValue: 5000,
        PaymentType: false,
    ));

    expect($cost)->not->toBeNull()
        ->and($cost->Value)->toBe(40.0)
        ->and($cost->MinValue)->toBe(10000.0);
});
