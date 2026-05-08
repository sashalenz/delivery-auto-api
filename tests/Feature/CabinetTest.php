<?php

declare(strict_types=1);

use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Support\Facades\Http;
use Sashalenz\DeliveryAuto\ApiModels\Cabinet\RequestData\GetUserReceiptRequest;
use Sashalenz\DeliveryAuto\ApiModels\Cabinet\RequestData\PostDeactivateReceiptsRequest;
use Sashalenz\DeliveryAuto\ApiModels\Cabinet\RequestData\PostLoginRequest;
use Sashalenz\DeliveryAuto\DeliveryAuto;
use Sashalenz\DeliveryAuto\Enums\ReceiptListType;
use Sashalenz\DeliveryAuto\Enums\ReceiptStatus;
use Sashalenz\DeliveryAuto\SessionStore;

it('does NOT attach HMAC headers on session-auth endpoints', function () {
    Http::fake([
        '*PostLogin*' => Http::response(['status' => true, 'message' => '']),
    ]);

    DeliveryAuto::cabinet()->postLogin(new PostLoginRequest(
        UserName: 'iv@test.ua',
        Password: 'secret',
    ));

    Http::assertSent(fn ($req) => empty($req->header('HMACAuthorization')) && $req->method() === 'POST');
});

it('loginFromConfig returns false when credentials are missing', function () {
    config([
        'delivery-auto-api.username' => null,
        'delivery-auto-api.password' => null,
    ]);

    expect(DeliveryAuto::cabinet()->loginFromConfig())->toBeFalse();
    Http::assertNothingSent();
});

it('postLogoff clears the session store', function () {
    Http::fake([
        '*PostLogoff*' => Http::response(['status' => true, 'message' => '']),
    ]);

    // Pre-populate the jar so we can prove it's cleared.
    SessionStore::store(new CookieJar);
    expect(SessionStore::cookies())->not->toBeNull();

    DeliveryAuto::cabinet()->postLogoff();

    expect(SessionStore::cookies())->toBeNull();
});

it('GetUserReceipt parses paginated response with typed enums', function () {
    Http::fake([
        '*GetUserReceipt*' => Http::response([
            'status' => true,
            'data' => [
                [
                    'id' => 'c26032e6-fd57-4b8a-827b-eb93a736a80b',
                    'number' => '9900043094',
                    'SendDate' => '2015-10-28T00:00:00',
                    'ReceiveDate' => '2015-10-29T00:00:00',
                    'SenderWarehouseName' => 'КИЇВ-1',
                    'RecepientWarehouseName' => 'КИЇВ-1',
                    'Status' => '8',
                    'StatusesDecoding' => 'Зарезервована',
                    'TotalCost' => 278.0,
                    'Currency' => 100000000,
                    'AuxServicesList' => [],
                    'PossibleReceivers' => [],
                ],
            ],
        ]),
    ]);

    $list = DeliveryAuto::cabinet()->getUserReceipt(new GetUserReceiptRequest(
        page: 1,
        rows: 10,
        type: ReceiptListType::Outgoing,
    ));

    expect($list)->toHaveCount(1)
        ->and($list[0]->number)->toBe('9900043094')
        ->and($list[0]->Status)->toBe(ReceiptStatus::Reserved);
});

it('PostDeactivateReceipts joins GUID array into comma-separated query', function () {
    Http::fake([
        '*PostDeactivateReceipts*' => Http::response(['status' => true, 'message' => '']),
    ]);

    DeliveryAuto::cabinet()->postDeactivateReceipts(new PostDeactivateReceiptsRequest(
        receiptsGuids: ['00000000-0000-0000-0000-000000000000', '11111111-1111-1111-1111-111111111111'],
    ));

    Http::assertSent(function ($req) {
        // PostDeactivateReceipts is POST — payload sits in JSON body, not URL.
        return $req->method() === 'POST'
            && $req['receiptsGuids'] === '00000000-0000-0000-0000-000000000000,11111111-1111-1111-1111-111111111111';
    });
});
