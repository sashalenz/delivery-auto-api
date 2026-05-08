# Upgrade guide: 1.x → 2.0

The 2.0 release is a breaking rewrite. The package's pre-2.0 baseline was a single commit (`6f3fab6`) likely with no production consumers, so this guide is short — but the changes are wide.

## TL;DR — what to update on the consumer side

1. **Composer constraint:** `^1.0` → `^2.0`.
2. **PHP / Laravel floor:** PHP `^8.2`, Laravel `^11.0||^12.0||^13.0`.
3. **Rename top-level imports**: `Delivery` → `DeliveryAuto`, `DeliveryException` → `DeliveryAutoException`, `DeliveryServiceProvider` → `DeliveryAutoServiceProvider`. The new facade name avoids colliding with consumer-side Eloquent models named `Delivery`.
4. **Replace `Delivery::warehouse()` / `Delivery::receipt()` calls** with the new domain-aligned facades on `DeliveryAuto`: `reference()`, `tracking()`, `calculation()`, `communication()`, `cabinet()`, `receipt()`, `logs()`.
5. **Replace `array $params` builder calls** with typed `*Request` DTOs from `Sashalenz\DeliveryAuto\ApiModels\<Module>\RequestData\`.
6. **Replace `Delivery::CURRENCY`** with `Sashalenz\DeliveryAuto\Enums\Currency::UAH` (or `->value` for the int).
7. **Wire up the new exception** `DeliveryAutoApiUnavailableException` in your error handlers (extends `DeliveryAutoException`).
8. **Re-publish the config**: file moved from `config/delivery-api.php` to `config/delivery-auto-api.php`, key prefix from `delivery-api.*` → `delivery-auto-api.*`. Env vars (`DELIVERY_API_PUBLIC_KEY`, etc.) are unchanged.

## Class-rename quick map

| 1.x | 2.0 |
|---|---|
| `Sashalenz\DeliveryAuto\Delivery` | `Sashalenz\DeliveryAuto\DeliveryAuto` |
| `Sashalenz\DeliveryAuto\DeliveryServiceProvider` | `Sashalenz\DeliveryAuto\DeliveryAutoServiceProvider` |
| `Sashalenz\DeliveryAuto\Exceptions\DeliveryException` | `Sashalenz\DeliveryAuto\Exceptions\DeliveryAutoException` |
| `config('delivery-api.public_key')` | `config('delivery-auto-api.public_key')` |

## 🔴 Critical: HMAC algorithm changed

The pre-2.0 implementation used **HMAC-SHA1**, but the vendor expects **HMAC-SHA256** (PDF v3.5.1 §6.1 — both the JS and C# reference snippets use `HmacSHA256`). This means **every `->auth()`-protected call in 1.x was silently broken**: the API rejected the signature.

If you were relying on `getSenderList`, `getClientCards`, `getClientInvoices`, `postCreateReceipts`, or `postCreateAddressOrClient` in 1.x and saw "API Exception" responses, that's why. 2.0 fixes the algorithm; no consumer change needed beyond bumping the version.

## Module facade rename

```diff
- DeliveryAuto::warehouse()->getRegionList(country: 1, culture: 'uk-UA')
+ DeliveryAuto::reference()->getRegionList(new GetRegionListRequest(
+     country: Country::UA, culture: Culture::UkUA
+ ))

- DeliveryAuto::receipt()->getReceiptDetails(number: '0830047053')
+ DeliveryAuto::tracking()->getReceiptDetails(new GetReceiptDetailsRequest(
+     number: '0830047053'
+ ))

- DeliveryAuto::receipt()->postReceiptCalculate([...])->post()->request()
+ DeliveryAuto::calculation()->postReceiptCalculate(new PostReceiptCalculateRequest(...))

- DeliveryAuto::receipt()->getCargoCategory()->auth()->request()  # was broken anyway
+ DeliveryAuto::calculation()->getCargoCategory(new GetCargoCategoryRequest())
```

Full mapping:

| 1.x location | 2.0 location |
|---|---|
| `DeliveryAuto::warehouse()->getRegionList/getAreasList/getWarehouses*` | `DeliveryAuto::reference()->...` |
| `DeliveryAuto::receipt()->getReceiptDetails/getDateArrival` | `DeliveryAuto::tracking()->...` |
| `DeliveryAuto::receipt()->getDopUslugiClassification/getTariffCategory/getDeliveryScheme/postReceiptCalculate/getCargoCategory` | `DeliveryAuto::calculation()->...` |
| `DeliveryAuto::receipt()->getClientCards/getClientInvoices/postCreateReceipts/getSenderList/postCreateAddressOrClient` | `DeliveryAuto::receipt()->...` |
| (none — new in 2.0) | `DeliveryAuto::cabinet()`, `DeliveryAuto::communication()`, `DeliveryAuto::logs()` |

## DTO migration

Every method now takes a `*Request` Spatie Data DTO and returns a typed `*Data` DTO or `DataCollection<Data>`.

```diff
- $regions = DeliveryAuto::warehouse()
-     ->country(1)
-     ->culture('uk-UA')
-     ->cache(3600)
-     ->request(); // Collection of arrays
+ $regions = DeliveryAuto::reference()
+     ->cache(3600)
+     ->getRegionList(new GetRegionListRequest(
+         country: Country::UA,
+         culture: Culture::UkUA,
+     )); // DataCollection<RegionData>
+
+ $first = $regions[0];
+ echo $first->name; // typed, IDE-completable
```

For methods with collection inputs (e.g. `postReceiptCalculate`, `postCreateReceipts`), wrap your category/receipt arrays in `Spatie\LaravelData\DataCollection`:

```php
new DataCollection(CategoryRequest::class, [
    ['categoryId' => '...', 'countPlace' => 1, 'helf' => 1, 'size' => 1],
])
```

## `DeliveryAuto::CURRENCY` removed

```diff
- 'currency' => DeliveryAuto::CURRENCY
+ 'currency' => Currency::UAH
+ // or, where an int is expected
+ 'currency' => Currency::UAH->value
```

The pre-2.0 constant was misnamed — `100000000` is the **currency code for UAH** in Delivery-Auto's dictionary §8.2, not a money divisor. Money values in calc responses are already in hryvnias; no division needed.

## Exception handling

Add a catch for the new subclass to route infrastructure-level failures separately from 4xx/application errors:

```php
use Sashalenz\DeliveryAuto\Exceptions\DeliveryAutoApiUnavailableException;
use Sashalenz\DeliveryAuto\Exceptions\DeliveryAutoException;

try {
    $result = DeliveryAuto::receipt()->postCreateReceipts($request);
} catch (DeliveryAutoApiUnavailableException $e) {
    // Catch FIRST — vendor down (5xx, ConnectionException, timeouts)
    // Treat as transient: queue + retry, route to monitoring as `warning`
} catch (DeliveryAutoException $e) {
    // 4xx, status:false. Application error or invalid input.
}
```

`DeliveryAutoApiUnavailableException extends DeliveryAutoException`, so existing single-catch sites that catch `DeliveryAutoException` keep working — they just don't get the routing benefit.

## Cabinet / login auth (new in 2.0)

If you need §5 personal-cabinet endpoints (user info, my receipts, my pickup orders, deactivate reserved TTNs) or §7 logs, you now need email+password in addition to the HMAC keys:

```dotenv
DELIVERY_API_USERNAME=me@example.com
DELIVERY_API_PASSWORD=secret
```

```php
DeliveryAuto::cabinet()->loginFromConfig();
$myTtns = DeliveryAuto::cabinet()->getUserReceipt(new GetUserReceiptRequest(page: 1, rows: 50));
DeliveryAuto::cabinet()->postLogoff();
```

In multi-tenant web apps where users own their own creds, skip `loginFromConfig` and call `postLogin` per request:

```php
DeliveryAuto::cabinet()->postLogin(new PostLoginRequest(
    UserName: $user->delivery_auto_email,
    Password: decrypt($user->delivery_auto_password),
));
```

The session is process-local — fresh per PHP request.

## Multi-sender (multiple HMAC keypairs)

If your app holds several Delivery-Auto company keypairs (one per sender), 2.0 lets you switch sender per call. There's no migration step here for 1.x apps (they only had one keypair via config), but worth knowing if you're planning to consolidate multiple senders into one Laravel app:

```php
// Per-call factory args
DeliveryAuto::receipt(
    publicKey: $sender->public_key,
    secretKey: $sender->secret_key,
)->postCreateReceipts($request);

// Or fluent — useful when the builder is composed elsewhere
DeliveryAuto::receipt()
    ->withCredentials($sender->public_key, $sender->secret_key)
    ->getSenderList(new GetSenderListRequest);
```

Without overrides, the builder falls back to `config('delivery-auto-api.public_key')` / `secret_key`. Cache keys hash the public key, so two senders never read each other's cached responses.

`DeliveryAuto::tracking(...)` accepts the same overrides for `getStickers` (the only HMAC method in that module).

## What's gone

Everything in `Sashalenz\DeliveryAuto\DataTransferObjects\*` is removed. References to `BaseDataTransferObject`, `RegionDataTransferObject`, etc. need to be replaced with the new module-namespaced DTOs.

`DeliveryAuto::warehouse()` and the legacy `DeliveryAuto::receipt()` builder API (`->method()->validate()->auth()->post()->dataKey()->cache()->request()`) are gone — that fluent surface is now internal protected on `BaseModel`. Public method names are the actual vendor method names (`getRegionList`, `postCreateReceipts`, etc.).

## Verification after upgrade

```bash
composer require sashalenz/delivery-auto-api:^2.0
composer require --dev pestphp/pest:^3.0  # if you want to mirror the test toolchain
php artisan vendor:publish --tag=delivery-auto-api-config --force
```

Smoke test against your dev keys:

```php
DeliveryAuto::reference()->getRegionList(new GetRegionListRequest(country: Country::UA))->count();
// Should return ≥25 — Ukraine has 24 oblasts + "Усі"

DeliveryAuto::receipt()->getSenderList(); // should return your senders, not throw
```

If `getSenderList` throws `DeliveryAutoException('API error. Невірний підпис')`, that's the SHA-1/SHA-256 mismatch — make sure the bumped composer constraint actually pulled 2.0 (`composer show sashalenz/delivery-auto-api`).
