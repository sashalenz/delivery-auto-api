# Delivery-Auto API

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sashalenz/delivery-auto-api.svg?style=flat-square)](https://packagist.org/packages/sashalenz/delivery-auto-api)
[![Total Downloads](https://img.shields.io/packagist/dt/sashalenz/delivery-auto-api.svg?style=flat-square)](https://packagist.org/packages/sashalenz/delivery-auto-api)

PHP/Laravel client for [Delivery-Auto](https://www.delivery-auto.com/) API v3.5.1 — references, tracking, calculator, receipt issuance, cabinet, and operation logs. Implements **all 38 documented endpoints**, with typed `spatie/laravel-data` request/response DTOs and Laravel-native HTTP retry/caching.

## Requirements

- PHP 8.2+
- Laravel 11, 12, or 13
- `spatie/laravel-data ^4.4`

## Installation

```bash
composer require sashalenz/delivery-auto-api
```

Publish the config:

```bash
php artisan vendor:publish --provider="Sashalenz\DeliveryAuto\DeliveryAutoServiceProvider" --tag="delivery-auto-api-config"
```

## Configuration

```php
// config/delivery-api.php
return [
    'url' => env('DELIVERY_API_URL', 'https://www.delivery-auto.com/api/v4/Public/'),

    // HMAC API keys — issued per company by Delivery-Auto, used for §6 (receipt) endpoints
    'public_key' => env('DELIVERY_API_PUBLIC_KEY'),
    'secret_key' => env('DELIVERY_API_SECRET_KEY'),

    // OPTIONAL — only for §5 (cabinet) and §7 (logs) endpoints, which require username/password
    'username' => env('DELIVERY_API_USERNAME'),
    'password' => env('DELIVERY_API_PASSWORD'),

    'shared_forms_url' => env('DELIVERY_API_SHARED_FORMS_URL', 'https://www.delivery-auto.com/'),
];
```

`.env`:

```dotenv
DELIVERY_API_PUBLIC_KEY=your-public-key
DELIVERY_API_SECRET_KEY=your-secret-key
```

### Two auth mechanisms

Delivery-Auto v4 ships with **two distinct auth flows**:

- **HMAC API key** (PDF §6.1) — used by all §6 receipt-issuance endpoints. Header `HMACAuthorization: amx {publicKey}:{timestamp}:{HmacSHA256}`. Algorithm is **SHA-256**, not SHA-1 (this was an upstream documentation gap that the pre-2.0 release of this package got wrong).
- **Login session** — used by §5 personal-cabinet and §7 log endpoints. Username/password via `PostLogin` returns a session cookie that subsequent calls reuse via Laravel's HTTP client cookie jar.

Public reference, tracking, calculation, and communication endpoints (§1–4) need no auth.

### Multi-sender (multiple HMAC keypairs)

If your app holds **several Delivery-Auto company keypairs** (one per sender / counterparty), pass them per call instead of relying on the global config:

```php
use Sashalenz\DeliveryAuto\DeliveryAuto;

// Per-call factory
DeliveryAuto::receipt(
    publicKey: $sender->public_key,
    secretKey: $sender->secret_key,
)->postCreateReceipts($request);

// Or fluent — handy when you've already obtained a builder
DeliveryAuto::receipt()
    ->withCredentials($sender->public_key, $sender->secret_key)
    ->cache(300)
    ->getSenderList(new GetSenderListRequest);
```

Without overrides the builder falls back to `config('delivery-auto-api.public_key')` / `secret_key`. Cache keys include a hash of the public key, so two senders never share cached responses.

`DeliveryAuto::tracking(...)` accepts the same overrides for `getStickers` (the only HMAC method in that module).

## Quick start — round-trip

```php
use Sashalenz\DeliveryAuto\Delivery;
use Sashalenz\DeliveryAuto\ApiModels\Reference\RequestData\GetRegionListRequest;
use Sashalenz\DeliveryAuto\ApiModels\Tracking\RequestData\GetReceiptDetailsRequest;
use Sashalenz\DeliveryAuto\ApiModels\Calculation\RequestData\PostReceiptCalculateRequest;
use Sashalenz\DeliveryAuto\ApiModels\Calculation\RequestData\CategoryRequest;
use Sashalenz\DeliveryAuto\ApiModels\Receipt\RequestData\PostCreateReceiptsRequest;
use Sashalenz\DeliveryAuto\ApiModels\Receipt\RequestData\RegistrationReceiptRequest;
use Sashalenz\DeliveryAuto\Enums\Country;
use Sashalenz\DeliveryAuto\Enums\DeliveryScheme;
use Spatie\LaravelData\DataCollection;

// 1) Lookup — cached for an hour because regions rarely change
$regions = DeliveryAuto::reference()
    ->cache(3600)
    ->getRegionList(new GetRegionListRequest(country: Country::UA));

// 2) Calculate cost
$calc = DeliveryAuto::calculation()->postReceiptCalculate(new PostReceiptCalculateRequest(
    areasSendId:        '4fc948a7-3729-e311-8b0d-00155d037960',
    areasResiveId:      'e3ac6f68-3529-e311-8b0d-00155d037960',
    warehouseSendId:    '1c828aa6-70c8-e211-9902-00155d037919',
    warehouseResiveId:  'd908c5e1-b36b-e211-81e9-00155d012a15',
    InsuranceValue:     1000000,
    CashOnDeliveryValue: 5000,
    dateSend:           new DateTime('+2 days'),
    deliveryScheme:     DeliveryScheme::WarehouseDoor,
    category: new DataCollection(CategoryRequest::class, [[
        'categoryId' => '00000000-0000-0000-0000-000000000000',
        'countPlace' => 1, 'helf' => 1, 'size' => 1,
    ]]),
));
echo $calc->allSumma; // total cost

// 3) Issue a TTN
$created = DeliveryAuto::receipt()->postCreateReceipts(new PostCreateReceiptsRequest(
    receiptsList: new DataCollection(RegistrationReceiptRequest::class, [
        new RegistrationReceiptRequest(
            areasSendId:        '4fc948a7-3729-e311-8b0d-00155d037960',
            areasResiveId:      'e3ac6f68-3529-e311-8b0d-00155d037960',
            dateSend:           new DateTime('+2 days'),
            category: new DataCollection(CategoryRequest::class, [[
                'categoryId' => '00000000-0000-0000-0000-000000000000',
                'countPlace' => 1, 'helf' => 1, 'size' => 1,
            ]]),
            deliveryScheme:     DeliveryScheme::WarehouseWarehouse,
            warehouseSendId:    '1c828aa6-70c8-e211-9902-00155d037919',
            warehouseResiveId:  'd908c5e1-b36b-e211-81e9-00155d012a15',
            receiverName:       'Іванков Іван Іванович',
            receiverPhone:      '0500000000',
        ),
    ]),
));
$ttnNumber = $created[0]->Number;

// 4) Track
$details = DeliveryAuto::tracking()->getReceiptDetails(
    new GetReceiptDetailsRequest(number: $ttnNumber)
);
echo $details->Status->label(); // "Зарезервована" if just created
```

## Modules

The seven module facades on `DeliveryAuto::*` mirror the seven sections of the PDF spec:

| Facade | PDF section | Auth | Purpose |
|---|---|---|---|
| `DeliveryAuto::reference()` | §1 Представництва | none | Region/city/warehouse directories |
| `DeliveryAuto::tracking()` | §2 Квитанції | none (HMAC for `getStickers`) | TTN status, ETA, sticker metadata |
| `DeliveryAuto::calculation()` | §3 Розрахунок | none | Tariff lookup, calculator, insurance cost |
| `DeliveryAuto::communication()` | §4 Зв'язок із користувачем | none | News, message themes, service rating, pickup vehicle order |
| `DeliveryAuto::cabinet()` | §5, §6.14 | login session | User info, my receipts, pickup orders, deactivate reserved TTNs, full receipt info |
| `DeliveryAuto::receipt()` | §6 Оформлення | HMAC | Cards/invoices/senders, create TTN, deactivate cargo units, PDF docs, address/client creation |
| `DeliveryAuto::logs()` | §7 | login session | Receipt operation logs |

### Reference

| Method | PDF | Returns |
|---|---|---|
| `getRegionList` | §1.1 | `DataCollection<RegionData>` |
| `getAreasList` (supports `cityName` autocomplete) | §1.2 | `DataCollection<AreaData>` |
| `getWarehousesList` | §1.3 | `DataCollection<WarehouseData>` |
| `getWarehousesInfo` | §1.4 | `WarehouseInfoData?` |
| `getWarehousesListByCity` | §1.5 | `DataCollection<WarehouseInfoData>` |
| `getFindWarehouses` (lat/lng + radius) | §1.6 | `DataCollection<WarehouseFindData>` |
| `getWarehousesListInDetail` | §1.7 | `DataCollection<WarehouseInfoData>` |

### Tracking

| Method | PDF | Returns |
|---|---|---|
| `getReceiptDetails` | §2.1 | `ReceiptData?` (status int → enum) |
| `getDateArrival` | §2.2 | `DateArrivalData?` |
| `getStickers` (HMAC) | §6.16 | `DataCollection<StickerData>` |

### Calculation

| Method | PDF | Returns |
|---|---|---|
| `getDopUslugiClassification` | §3.2 | `DataCollection<DopUslugaClassificationData>` |
| `getTariffCategory` | §3.3 | `DataCollection<TariffCategoryData>` |
| `getCargoCategory` (was wrongly auth-only pre-2.0) | §3.4 | `DataCollection<CargoCategoryData>` |
| `getDeliveryScheme` | §3.5 | `DataCollection<DeliverySchemeData>` |
| `postReceiptCalculate` | §3.6 | `CalculationData?` |
| `getInsuranceCost` | §3.7 | `InsuranceCostData?` |

### Communication

| Method | PDF | Returns |
|---|---|---|
| `getNews` (paginated) | §4.2 | `DataCollection<NewsItemData>` |
| `getMessagesTheme` | §4.3 | `DataCollection<MessageThemeData>` |
| `postServiceRate` | §4.4 | `bool` |
| `postPickUpCargo` | §4.5 | `bool` |

### Cabinet (login-auth)

```php
// Either pass credentials each time …
DeliveryAuto::cabinet()->postLogin(new PostLoginRequest(
    UserName: 'me@example.com', Password: 'secret'
));

// … or set DELIVERY_API_USERNAME / DELIVERY_API_PASSWORD env vars and call:
DeliveryAuto::cabinet()->loginFromConfig();
```

| Method | PDF | Returns |
|---|---|---|
| `postLogin` | §5.1 | `bool` (cookie persists in `SessionStore`) |
| `postLogoff` | §5.2 | `bool` (clears session) |
| `getUserInfo` | §5.3 | `UserInfoData?` |
| `getUserReceipt` | §5.4 | `DataCollection<UserReceiptData>` |
| `getUserPickUp` | §5.5 | `DataCollection<UserPickUpData>` |
| `postDeactivateReceipts` (cancel reserved TTNs) | §5.6 | `bool` |
| `getFullReceiptInformation` | §6.14 | `FullReceiptInformationData?` |

⚠️ The session is process-local. Multi-tenant web apps should call `postLogin` per request with the right credentials, not rely on `loginFromConfig`.

### Receipt (HMAC-auth)

| Method | PDF | Returns |
|---|---|---|
| `getClientCards` | §6.2 | `DataCollection<ClientCardData>` |
| `getClientInvoices` | §6.3 | `DataCollection<ClientInvoiceData>` |
| `postCreateReceipts` | §6.4 | `DataCollection<CreatedReceiptData>` |
| `postDeactivateEg` | §6.5 | `bool` |
| `getPdfDocument` (base64-decoded PDF) | §6.6 | `PdfDocumentData?` |
| `getSenderList` | §6.7 | `DataCollection<SenderData>` |
| `getCurrency` | §6.8 | `DataCollection<SimpleNamedItemData>` |
| `getAvailableServices` | §6.9 | `AvailableServicesData?` |
| `getPayer` | §6.10 | `DataCollection<SimpleNamedItemData>` |
| `getClientAddress` | §6.11 | `DataCollection<SimpleNamedItemData>` |
| `getPosibleReciver` | §6.12 | `DataCollection<SimpleNamedItemData>` |
| `getClientPaymentType` | §6.13 | `bool` (cash vs cashless) |
| `postCreateAddressOrClient` | §6.15 | `CreatedAddressOrClientData?` |
| `postAddReceiptIntoPickUpRequest` | §6.17 | `bool` |

### Logs (login-auth)

| Method | PDF | Returns |
|---|---|---|
| `getUnidersalLogsByReceiptNumber` (sic — vendor typo) | §7.1 | `DataCollection<ReceiptLogData>` |

### SendingRegister URL builder

§6.18 returns HTML, not JSON, so we expose only a URL builder (no HTTP call):

```php
use Sashalenz\DeliveryAuto\SendingRegisterUrlBuilder;
use Sashalenz\DeliveryAuto\Enums\Culture;

$url = SendingRegisterUrlBuilder::url(
    id: '00123456',
    type: SendingRegisterUrlBuilder::FORM_TYPE_DETAILED,
    culture: Culture::UkUA,
);
// https://www.delivery-auto.com/uk-UA/SharedForms/SendingRegister?id=00123456&type=0
```

## Caching

Every module method supports `->cache($seconds)`. `seconds = -1` (default) means cache forever:

```php
$regions = DeliveryAuto::reference()->cache(3600)->getRegionList(...);
$schemes = DeliveryAuto::calculation()->cache(86400)->getDeliveryScheme(...);
```

Cache keys are derived from `(method, GET/POST, authMode, base64(serialize(params)))` — same params hit the same entry.

⚠️ Don't cache user-scoped (cabinet/logs) calls — the session cookie is implicit, but params alone don't differentiate users.

## Enums cheat-sheet

| Enum | Type | Values |
|---|---|---|
| `Currency` | int | `UAH = 100000000` (PDF §8.2) |
| `Country` | int | `UA = 1` |
| `Culture` | string | `UkUA`, `EnUS` |
| `ReceiptStatus` | int | 14 states (0..13), with `isFinal()`/`isSuccess()`/`isInTransit()` |
| `ReceiptType` | int | 10 types (PDF §8.4) |
| `OrderState` | int | 4 states (PDF §8.5), with `isOpen()`/`isCancelled()` |
| `OperationCode` | int | Log operation codes (PDF §8.3) |
| `DeliveryScheme` | int | `WarehouseWarehouse=0`, `DoorDoor=1`, `WarehouseDoor=2`, `DoorWarehouse=3`, with `requiresPickupAddress()`/etc. |
| `WarehouseType` | int | `Standard=0`, `CashTransferEnabled=3` |
| `DocumentType` | int | `Receipt=0`, `StickersPerPage=1`, `StickersOneSheet=2`, `MultiReceipt95x95=4` |
| `PayerType` | int | `Sender=0`, `Receiver=1`, `ThirdParty=2` |
| `PaymentType` | int | `Cash=0`, `Cashless=1` |
| `CashOnDeliveryType` | int | `Card=0`, `Invoice=1`, `Cash=2` |
| `DirectionType` | int | `Send=0`, `Receive=1` |
| `ReceiptListType` | int | `Outgoing=0`, `Incoming=1` |

Use the enum in DTO requests directly: `new GetRegionListRequest(country: Country::UA)`. In responses, `EnumCast` parses raw API integers into typed enum cases — match on them, never on raw strings.

## Error handling

```php
use Sashalenz\DeliveryAuto\Exceptions\DeliveryAutoException;
use Sashalenz\DeliveryAuto\Exceptions\DeliveryAutoApiUnavailableException;

try {
    $result = DeliveryAuto::receipt()->postCreateReceipts($request);
} catch (DeliveryAutoApiUnavailableException $e) {
    // Catch FIRST — vendor is down (5xx, ConnectionException, DNS, timeout).
    // Retry with backoff; route to monitoring as warning, not error.
    Log::warning('delivery-auto unavailable', ['exception' => $e]);
    throw $e; // or queue and retry
} catch (DeliveryAutoException $e) {
    // 4xx, application-level `status: false`. Real bug or invalid input.
    Log::error('delivery-auto request failed', ['exception' => $e]);
    throw $e;
}
```

| Exception | Triggered by | Severity |
|---|---|---|
| `DeliveryAutoApiUnavailableException` | `ConnectionException`, 5xx response | warning (transient infra) |
| `DeliveryAutoException` | 4xx response, `status: false` payload | error (application bug or bad input) |

The HTTP layer auto-retries 3× with 100ms backoff before exceptions surface. Both exceptions wrap the original via `previous: $e` so root cause survives in logs.

### Bugsnag routing snippet

```php
// app/Exceptions/Handler.php (or via Bugsnag::registerCallback)
Bugsnag::registerCallback(function (Bugsnag\Report $report) {
    if ($report->getOriginalError() instanceof DeliveryAutoApiUnavailableException) {
        $report->setSeverity('warning');
    }
});
```

## Print labels & stickers

```php
use Sashalenz\DeliveryAuto\ApiModels\Receipt\RequestData\GetPdfDocumentRequest;
use Sashalenz\DeliveryAuto\Enums\DocumentType;

// Returns base64-encoded PDF — `binary()` decodes to raw bytes.
$pdf = DeliveryAuto::receipt()->getPdfDocument(new GetPdfDocumentRequest(
    number: ['9900112233', '9900223344'],
    type:   DocumentType::StickersOneSheet,
));

return response($pdf->binary(), 200, ['Content-Type' => 'application/pdf']);
```

For sticker JSON metadata (without the PDF render), use `DeliveryAuto::tracking()->getStickers(...)`.

## Testing

```bash
composer test           # run pest
composer test-coverage  # with coverage
composer analyse        # phpstan level 6
composer format-test    # pint
```

## Changelog

See [CHANGELOG.md](CHANGELOG.md). The 2.0 release is a breaking rewrite — see [UPGRADING.md](UPGRADING.md) for migration notes from 1.x.

## Security

If you discover a security vulnerability, please email sashalenz@gmail.com instead of opening a public issue.

## Credits

- [Oleksandr Petrovskyi](https://github.com/sashalenz)
- All Contributors

## License

MIT — see [LICENSE.md](LICENSE.md).
