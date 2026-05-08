<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use Sashalenz\DeliveryAuto\ApiModels\Receipt\RequestData\GetSenderListRequest;
use Sashalenz\DeliveryAuto\DeliveryAuto;

it('signs the request with credentials passed to the receipt() factory, not config', function () {
    Http::fake([
        '*GetSenderList*' => Http::response(['status' => true, 'data' => []]),
    ]);

    DeliveryAuto::receipt(
        publicKey: 'AAAA1111-AAAA-AAAA-AAAA-AAAAAAAAAAAA',
        secretKey: 'sender-A-secret',
    )->getSenderList(new GetSenderListRequest);

    Http::assertSent(fn ($req) => str_starts_with(
        $req->header('HMACAuthorization')[0] ?? '',
        'amx AAAA1111-AAAA-AAAA-AAAA-AAAAAAAAAAAA:'
    ));
});

it('routes two separate calls through different keypairs without leaking state', function () {
    Http::fake([
        '*GetSenderList*' => Http::response(['status' => true, 'data' => []]),
    ]);

    DeliveryAuto::receipt(publicKey: 'KEY-A', secretKey: 'sec-A')
        ->getSenderList(new GetSenderListRequest);

    DeliveryAuto::receipt(publicKey: 'KEY-B', secretKey: 'sec-B')
        ->getSenderList(new GetSenderListRequest);

    $sentAuth = Http::recorded()->map(fn ($pair) => $pair[0]->header('HMACAuthorization')[0]);

    expect($sentAuth)->toHaveCount(2)
        ->and($sentAuth[0])->toStartWith('amx KEY-A:')
        ->and($sentAuth[1])->toStartWith('amx KEY-B:');
});

it('withCredentials() is fluent and overrides config-level keys', function () {
    Http::fake([
        '*GetSenderList*' => Http::response(['status' => true, 'data' => []]),
    ]);

    DeliveryAuto::receipt()
        ->withCredentials('FLUENT-PUB', 'fluent-sec')
        ->getSenderList(new GetSenderListRequest);

    Http::assertSent(fn ($req) => str_starts_with(
        $req->header('HMACAuthorization')[0] ?? '',
        'amx FLUENT-PUB:'
    ));
});

it('falls back to config keys when no override is given', function () {
    config([
        'delivery-auto-api.public_key' => 'CONFIG-DEFAULT-PUB',
        'delivery-auto-api.secret_key' => 'config-default-secret',
    ]);

    Http::fake([
        '*GetSenderList*' => Http::response(['status' => true, 'data' => []]),
    ]);

    DeliveryAuto::receipt()->getSenderList(new GetSenderListRequest);

    Http::assertSent(fn ($req) => str_starts_with(
        $req->header('HMACAuthorization')[0] ?? '',
        'amx CONFIG-DEFAULT-PUB:'
    ));
});

it('cache keys differ per sender so two senders do not share a cached response', function () {
    // fakeSequence — first invocation returns A, second returns B. If our cache
    // key were sender-agnostic, the second call would hit cache and never
    // consume B; with per-sender keys, both calls travel over HTTP.
    Http::fakeSequence()
        ->push(['status' => true, 'data' => [['id' => 'sender-A', 'name' => 'A', 'cityId' => 'c', 'cityName' => 'C']]])
        ->push(['status' => true, 'data' => [['id' => 'sender-B', 'name' => 'B', 'cityId' => 'c', 'cityName' => 'C']]]);

    $a = DeliveryAuto::receipt(publicKey: 'KEY-A', secretKey: 'sec-A')
        ->cache(60)
        ->getSenderList(new GetSenderListRequest);

    $b = DeliveryAuto::receipt(publicKey: 'KEY-B', secretKey: 'sec-B')
        ->cache(60)
        ->getSenderList(new GetSenderListRequest);

    expect($a[0]->name)->toBe('A')
        ->and($b[0]->name)->toBe('B');

    // And calling sender A a second time should hit cache — no third HTTP call.
    $aAgain = DeliveryAuto::receipt(publicKey: 'KEY-A', secretKey: 'sec-A')
        ->cache(60)
        ->getSenderList(new GetSenderListRequest);

    Http::assertSentCount(2);
    expect($aAgain[0]->name)->toBe('A');
});
