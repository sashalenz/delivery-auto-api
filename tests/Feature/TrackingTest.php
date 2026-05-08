<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use Sashalenz\DeliveryAuto\ApiModels\Tracking\RequestData\GetDateArrivalRequest;
use Sashalenz\DeliveryAuto\ApiModels\Tracking\RequestData\GetReceiptDetailsRequest;
use Sashalenz\DeliveryAuto\DeliveryAuto;
use Sashalenz\DeliveryAuto\Enums\ReceiptStatus;
use Sashalenz\DeliveryAuto\Enums\ReceiptType;
use Sashalenz\DeliveryAuto\Exceptions\DeliveryAutoApiUnavailableException;
use Sashalenz\DeliveryAuto\Exceptions\DeliveryAutoException;

it('parses GetReceiptDetails into ReceiptData with typed enums', function () {
    Http::fake([
        '*GetReceiptDetails*' => Http::response([
            'status' => true,
            'message' => '',
            'data' => [
                'id' => '045905c9-b17b-4ccb-8e85-8ec7f5b548e2',
                'number' => '0830047053',
                'SendDate' => '2014-06-05T09:54:20',
                'ReceiveDate' => '2014-06-07T09:54:20',
                'CreatedDate' => '2014-06-05T06:52:50',
                'SenderWarehouseName' => 'КИЇВ-02',
                'RecepientWarehouseName' => 'ЧЕРНІВЦІ-2',
                'TotalCost' => 24.5,
                'Status' => 0,
                'Type' => 2,
                'Currency' => 100000000,
                'StatusesDecoding' => 'Видана',
            ],
        ]),
    ]);

    $receipt = DeliveryAuto::tracking()->getReceiptDetails(new GetReceiptDetailsRequest(number: '0830047053'));

    expect($receipt)->not->toBeNull()
        ->and($receipt->number)->toBe('0830047053')
        ->and($receipt->Status)->toBe(ReceiptStatus::Issued)
        ->and($receipt->Status->isSuccess())->toBeTrue()
        ->and($receipt->Type)->toBe(ReceiptType::Regular)
        ->and($receipt->StatusesDecoding)->toBe('Видана')
        ->and($receipt->SendDate)->not->toBeNull()
        ->and($receipt->SendDate->format('Y-m-d'))->toBe('2014-06-05');
});

it('parses Reserved (status=8) receipt status as in-progress, not final', function () {
    Http::fake([
        '*GetReceiptDetails*' => Http::response([
            'status' => true,
            'data' => ['id' => 'a', 'number' => 'b', 'Status' => 8],
        ]),
    ]);

    $receipt = DeliveryAuto::tracking()->getReceiptDetails(new GetReceiptDetailsRequest(number: 'b'));

    expect($receipt->Status)->toBe(ReceiptStatus::Reserved)
        ->and($receipt->Status->isFinal())->toBeFalse();
});

it('parses GetDateArrival ISO-with-TZ and short-string date formats', function () {
    Http::fake([
        '*GetDateArrival*' => Http::response([
            'status' => true,
            'data' => [
                'arrivalDate' => '2021-09-20T16:00:00+03:00',
                'sendDate' => '2021-09-17T20:00:00+03:00',
                'arrivalDateStr' => '20.09.2021',
                'sendDateStr' => '17.09.2021',
            ],
        ]),
    ]);

    $eta = DeliveryAuto::tracking()->getDateArrival(new GetDateArrivalRequest(
        areasSendId: '4fc948a7-3729-e311-8b0d-00155d037960',
        areasResiveId: 'e3ac6f68-3529-e311-8b0d-00155d037960',
        dateSend: new DateTime('2021-09-17'),
    ));

    expect($eta)->not->toBeNull()
        ->and($eta->arrivalDate?->format('Y-m-d H:i'))->toBe('2021-09-20 16:00')
        ->and($eta->arrivalDateStr)->toBe('20.09.2021');
});

it('routes server errors as DeliveryAutoApiUnavailableException', function () {
    Http::fake([
        '*GetReceiptDetails*' => Http::response('boom', 502),
    ]);

    expect(fn () => DeliveryAuto::tracking()->getReceiptDetails(new GetReceiptDetailsRequest(number: 'x')))
        ->toThrow(DeliveryAutoApiUnavailableException::class);
});

it('routes 4xx as plain DeliveryAutoException', function () {
    Http::fake([
        '*GetReceiptDetails*' => Http::response(['error' => 'not found'], 404),
    ]);

    try {
        DeliveryAuto::tracking()->getReceiptDetails(new GetReceiptDetailsRequest(number: 'x'));
        expect(false)->toBeTrue('expected throw');
    } catch (DeliveryAutoException $e) {
        expect($e)->not->toBeInstanceOf(DeliveryAutoApiUnavailableException::class);
    }
});
