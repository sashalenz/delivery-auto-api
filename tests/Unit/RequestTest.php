<?php

declare(strict_types=1);

use Carbon\Carbon;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Sashalenz\DeliveryAuto\Exceptions\DeliveryAutoApiUnavailableException;
use Sashalenz\DeliveryAuto\Exceptions\DeliveryAutoException;
use Sashalenz\DeliveryAuto\Request;

beforeEach(function () {
    Carbon::setTestNow(Carbon::createFromTimestamp(1700000000)); // deterministic timestamp
});

afterEach(function () {
    Carbon::setTestNow();
});

it('signs HMAC-authed requests with SHA-256, not the legacy SHA-1', function () {
    Http::fake([
        '*' => Http::response(['status' => true, 'message' => '', 'data' => []]),
    ]);

    $request = new Request(
        method: 'GetSenderList',
        params: [],
        isPost: false,
        authMode: Request::AUTH_HMAC,
    );
    $request->make();

    $publicKey = (string) config('delivery-auto-api.public_key');
    $secretKey = (string) config('delivery-auto-api.secret_key');
    $expectedHash = hash_hmac('sha256', $publicKey.'1700000000', $secretKey);

    // SHA-1 from the same input would produce a different hash; assert the real one.
    Http::assertSent(function ($req) use ($publicKey, $expectedHash) {
        $auth = $req->header('HMACAuthorization')[0] ?? '';

        return $auth === 'amx '.$publicKey.':1700000000:'.$expectedHash;
    });
});

it('does not attach HMAC auth on public endpoints', function () {
    Http::fake([
        '*' => Http::response(['status' => true, 'message' => '', 'data' => []]),
    ]);

    $request = new Request(
        method: 'GetRegionList',
        params: ['country' => 1],
    );
    $request->make();

    Http::assertSent(fn ($req) => empty($req->header('HMACAuthorization')));
});

it('throws DeliveryAutoApiUnavailableException on 5xx responses', function () {
    Http::fake([
        '*' => Http::response('boom', 503),
    ]);

    $request = new Request(method: 'GetRegionList', params: []);

    expect(fn () => $request->make())
        ->toThrow(DeliveryAutoApiUnavailableException::class);
});

it('throws DeliveryAutoException on 4xx responses', function () {
    Http::fake([
        '*' => Http::response(['error' => 'bad'], 422),
    ]);

    $request = new Request(method: 'GetRegionList', params: []);

    try {
        $request->make();
        expect(false)->toBeTrue('expected throw');
    } catch (DeliveryAutoException $e) {
        // 4xx must NOT be wrapped as ApiUnavailable
        expect($e)->not->toBeInstanceOf(DeliveryAutoApiUnavailableException::class);
    }
});

it('throws DeliveryAutoApiUnavailableException on connection failures', function () {
    Http::fake(function () {
        throw new ConnectionException('curl: timeout');
    });

    $request = new Request(method: 'GetRegionList', params: []);

    expect(fn () => $request->make())
        ->toThrow(DeliveryAutoApiUnavailableException::class);
});

it('throws DeliveryAutoException when the API returns status:false', function () {
    Http::fake([
        '*' => Http::response([
            'status' => false,
            'code' => 401,
            'message' => 'Невірний логін або пароль',
        ]),
    ]);

    $request = new Request(method: 'PostLogin', params: [], isPost: true);

    expect(fn () => $request->make())
        ->toThrow(DeliveryAutoException::class, 'Невірний логін або пароль');
});

it('extracts the configured data key from the response envelope', function () {
    Http::fake([
        '*' => Http::response([
            'status' => true,
            'message' => '',
            'receipts' => [['Id' => 'abc', 'Number' => '123']],
        ]),
    ]);

    $request = new Request(
        method: 'PostCreateReceipts',
        params: [],
        isPost: true,
        dataKey: 'receipts',
    );
    $payload = $request->make();

    expect($payload->all())->toBe([['Id' => 'abc', 'Number' => '123']]);
});

it('returns the envelope-stripped payload for endpoints with root-level data', function () {
    Http::fake([
        '*' => Http::response([
            'status' => true,
            'message' => '',
            'Value' => 40.0,
            'MinValue' => 10000.0,
        ]),
    ]);

    $request = new Request(
        method: 'GetInsuranceCost',
        params: [],
        dataKey: Request::DATA_KEY_ROOT,
    );
    $payload = $request->make();

    expect($payload->all())->toBe(['Value' => 40, 'MinValue' => 10000]);
});

it('caches successful responses by composite key', function () {
    Http::fake([
        '*' => Http::response(['status' => true, 'data' => [['id' => 1]]]),
    ]);

    $request = new Request(method: 'GetRegionList', params: ['country' => 1]);
    $first = $request->cache(60);

    Http::fake([
        '*' => Http::response(['status' => true, 'data' => [['id' => 999]]]),
    ]);

    // Second request — same params, same method — should hit cache.
    $second = (new Request(method: 'GetRegionList', params: ['country' => 1]))->cache(60);

    expect($first->all())->toBe([['id' => 1]])
        ->and($second->all())->toBe([['id' => 1]]);
});
