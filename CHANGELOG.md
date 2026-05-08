# Changelog

All notable changes to `sashalenz/delivery-auto-api` will be documented in this file. Format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/) and [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.0] - 2026-05-08

Breaking rewrite. Implements the full PDF v3.5.1 endpoint surface (38 endpoints across 7 modules), modernises the toolchain to PHP 8.2+ / Laravel 11–13, and migrates DTOs to `spatie/laravel-data`. See [UPGRADING.md](UPGRADING.md) for migration notes.

### Added

- **All §1–7 endpoints** from PDF v3.5.1 are now implemented (38 total). Pre-1.x shipped with ~13. New endpoints added in 2.0:
  - §3.7 `getInsuranceCost` — calculate insurance fee for a route + declared value
  - §4.2 `getNews`, §4.3 `getMessagesTheme`, §4.4 `postServiceRate`, §4.5 `postPickUpCargo` — public communication endpoints
  - §5.1 `postLogin`, §5.2 `postLogoff`, §5.3 `getUserInfo`, §5.4 `getUserReceipt`, §5.5 `getUserPickUp`, §5.6 `postDeactivateReceipts` — login-session cabinet
  - §6.5 `postDeactivateEg` — deactivate cargo units
  - §6.6 `getPdfDocument` — fetch base64-encoded PDFs (receipts, stickers, multi-receipt sheets)
  - §6.8 `getCurrency`, §6.9 `getAvailableServices`, §6.10 `getPayer`, §6.11 `getClientAddress`, §6.12 `getPosibleReciver`, §6.13 `getClientPaymentType`
  - §6.14 `getFullReceiptInformation` — login-auth, lives in Cabinet module despite being in §6 of the PDF
  - §6.16 `getStickers`, §6.17 `postAddReceiptIntoPickUpRequest`
  - §6.18 `SendingRegisterUrlBuilder` — URL helper for the HTML print-form endpoint
  - §7.1 `getUnidersalLogsByReceiptNumber` — receipt operation log
- **`DeliveryAutoApiUnavailableException`** — subclass of `DeliveryAutoException`. Thrown on `ConnectionException`, DNS failures, timeouts, and 5xx responses, so consumers can route transient infra issues to a `warning` severity bucket separately from 4xx/`status:false` application errors.
- **13 typed enums** in `Sashalenz\DeliveryAuto\Enums\*`: `Currency`, `Country`, `Culture`, `ReceiptStatus`, `ReceiptType`, `OrderState`, `OperationCode`, `DeliveryScheme`, `WarehouseType`, `DocumentType`, `PayerType`, `PaymentType`, `CashOnDeliveryType`, `DirectionType`, `ReceiptListType`. `ReceiptStatus` ships with helper methods `isFinal()`, `isSuccess()`, `isInTransit()`. `DeliveryScheme` ships with `requiresPickupAddress()`/`requiresDeliveryAddress()`/etc.
- **`SessionStore`** singleton holding the login-session cookie jar for §5/§7 endpoints. `DeliveryAuto::cabinet()->postLogoff()` clears it.
- **Multi-sender HMAC credential override.** Apps with several Delivery-Auto company keypairs can now route a single request to a specific sender via `DeliveryAuto::receipt(publicKey: ..., secretKey: ...)` (factory-arg form) or `->withCredentials($pub, $sec)` (fluent form). Cache keys include a hash of the public key so different senders don't share cached responses.
- **`DeliveryAuto::cabinet()->loginFromConfig()`** convenience for single-tenant CLI/queue contexts using `DELIVERY_API_USERNAME`/`DELIVERY_API_PASSWORD` env vars.
- **`CarbonInterfaceCast`** + **`CarbonInterfaceTransformer`** — handle the multiple date formats vendor returns (`Y-m-d\TH:i:s`, `Y-m-d\TH:i:sP`, `d.m.Y`, `d.m.Y H:i:s`).
- **`DeliveryAuto::DATA_KEY_ROOT` marker** on `Request` for endpoints (`GetInsuranceCost`, `GetPdfDocument`) whose payload sits at the response root rather than under `data`.
- **Pest test suite** with `Http::fake()` fixtures derived from PDF examples — 44 tests across Unit + Feature suites.
- **PHPStan level 6** + **Pint** + **GitHub Actions CI matrix** (PHP 8.2/8.3/8.4 × Laravel 11/12/13).
- **`UPGRADING.md`** describing the 1.x → 2.0 migration.

### Changed

- **🔴 BREAKING — HMAC algorithm fixed: SHA-1 → SHA-256.** Per PDF v3.5.1 §6.1, both the JS and C# reference implementations specify `HmacSHA256`. Pre-2.0 code used `hash_hmac('sha1', ...)`, which means **every auth-protected method was silently broken** — the vendor would reject the signature. This is the headline fix in 2.0.
- **🔴 BREAKING — minimum PHP `^8.1` → `^8.2`** and Laravel `^9.0|^10.0` → `^11.0|^12.0|^13.0`. Required for `spatie/laravel-data ^4`.
- **🔴 BREAKING — `DeliveryAuto::CURRENCY = 100000000` constant removed.** It was misnamed: per PDF §8.2 the value is the **currency code for UAH**, not a money divisor. Use `Sashalenz\DeliveryAuto\Enums\Currency::UAH` instead.
- **🔴 BREAKING — top-level facade renamed `Delivery` → `DeliveryAuto`.** Avoids collisions with consumer-side `Delivery` Eloquent models. Same applies to:
  - `Sashalenz\DeliveryAuto\DeliveryServiceProvider` → `DeliveryAutoServiceProvider`
  - `Sashalenz\DeliveryAuto\Exceptions\DeliveryException` → `DeliveryAutoException`
  - config file path: `config/delivery-api.php` → `config/delivery-auto-api.php`; config keys move from `delivery-api.*` → `delivery-auto-api.*` (env vars `DELIVERY_API_*` are unchanged for ease of deploy).
- **🔴 BREAKING — module facades restructured.** `Delivery::warehouse()` and the legacy `Delivery::receipt()` builder are gone, replaced with seven domain-aligned facades: `reference()`, `tracking()`, `calculation()`, `communication()`, `cabinet()`, `receipt()`, `logs()`. The receipt facade now narrowly handles only HMAC-authed §6 issuance methods.
- **🔴 BREAKING — DTOs migrated from `spatie/data-transfer-object` to `spatie/laravel-data`.** Every request takes a typed `*Request` DTO; every response returns a typed `*Data` DTO or `DataCollection`. The pre-2.0 `BaseDataTransferObject::fromArray()` and `array $params` builder API are gone.
- `Request::make()` now returns `Illuminate\Support\Collection` instead of an array, and exception flow distinguishes infrastructure from application errors. Both exceptions wrap the original `previous: $e` so stack traces survive.
- Module builders now ship a `reset()` between calls so reusing a module instance across multiple requests no longer leaks state from the previous call.

### Fixed

- **`getCargoCategory` (§3.4)** — pre-2.0 incorrectly marked it as auth-required and omitted the `TariffCategoryId` query param. Per PDF §3.4 it is a **public** endpoint accepting an optional `TariffCategoryId` GUID and `culture`. Now fixed.
- **Missing query params** restored across §1 endpoints:
  - `GetAreasList` now accepts `cityName` for autocomplete (this is the "address autocomplete" the prior gap analysis listed as missing — it was already supported by the API)
  - `GetWarehousesList`: `needCenterPickUpDelivery`
  - `GetFindWarehouses`: `Type`, `country`
  - `GetWarehousesListInDetail`: `includeRegionalCenters`, `needCenterPickUpDelivery`
- `Request` retry/cache/auth state is no longer mutated after the first call — fresh state per invocation.

### Removed

- `Sashalenz\DeliveryAuto\ApiModels\Warehouse` — split into `Reference\Reference` (§1) and parts of `Tracking\Tracking`.
- `Sashalenz\DeliveryAuto\ApiModels\Receipt` (the legacy mega-class) — split across `Tracking`, `Calculation`, `Receipt`, `Cabinet`, `Logs`.
- `Sashalenz\DeliveryAuto\DataTransferObjects\*` — replaced with per-module `RequestData/` and `ResponseData/` Spatie Data classes.
- `Sashalenz\DeliveryAuto\DeliveryAuto::CURRENCY` constant — replaced with `Currency` enum.
- Legacy `psalm.xml.dist` and `.php_cs.dist` — replaced with `phpstan.neon` and `pint.json`.

## [1.0.0] - 2022-XX-XX

- Initial single-commit baseline (`6f3fab6`). Partial coverage of §1, §2, §3, §6 endpoints. Custom `BaseDataTransferObject`, HMAC-SHA1 (broken — see 2.0).
