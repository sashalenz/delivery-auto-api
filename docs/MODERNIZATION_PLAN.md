# DeliveryAuto API package — modernization plan

> **Status:** 📋 Planned — single-commit baseline (`6f3fab6`), needs L13/PHP 8.5 bump + DTO migration + tests + missing methods + docs.
>
> **Reference template:** [`sashalenz/nova-poshta-api`](https://github.com/sashalenz/nova-poshta-api) (latest: `3.4.0`). NP-API is the most up-to-date sibling package and the canonical pattern for Sashalenz Laravel SDKs — mirror its `Request.php` retry/exception flow, `BaseModel` builder shape, `spatie/laravel-data`-typed `RequestData/ResponseData` classes, exception hierarchy, README structure, and CHANGELOG style.
>
> **Vendor API spec:** [`API_Delivery_v3_5_1.pdf`](API_Delivery_v3_5_1.pdf) — bundled in this directory. Use as the source of truth for endpoint coverage and field semantics. Cross-check every implemented method against this PDF before tagging a release.
>
> **Consumer:** the package will plug into [`a20-manager-laravel`](https://github.com/a20ua/a20-manager-laravel) as the foundation of an upcoming `Services/DeliveryServices/DeliveryAuto/` module (M3.2 milestone). Don't write a20-specific code in this package — keep it provider-agnostic and idiomatic.

---

## 1. Current state audit

### 1.1 What's there (`6f3fab6 first commit`)

**Composer:**
- `php ^8.1`
- `illuminate/contracts ^9.0|^10.0`
- `nesbot/carbon ^2.57`
- `spatie/laravel-package-tools ^1.9`
- No dev tooling beyond `roave/security-advisories`

**Architecture — fluent builder:**
- `Sashalenz\DeliveryAuto\Delivery` — facade (`Delivery::warehouse()`, `Delivery::receipt()`)
- `ApiModels/BaseModel.php` — base builder with chain `->method()->validate()->auth()->post()->dataKey()->cache()->request()`
- `Request.php` — Laravel HTTP client with retry (3×100ms) + 10s timeout + HMAC SHA1 auth header
- `Exceptions/DeliveryException.php` — single base exception
- DTOs via custom `BaseDataTransferObject` (NOT `spatie/laravel-data` — old pattern)

**Implemented endpoints:**

`Warehouse` (read-only references):
- `getRegionList(country)` — regions
- `getAreasList(regionId, country)` — settlements
- `getWarehousesList(CityId / RegionId / country)` — warehouses
- `getWarehousesInfo(WarehousesId)` — warehouse details
- `getFindWarehouses(CityId, Longitude, Latitude, count)` — nearest by coords
- `getWarehousesListInDetail(country, onlyWarehouses, CityId)` — full list

`Receipt` (mixed):
- `getReceiptDetails(number)` — TTN status (single)
- `getDateArrival(...)` — ETA estimate
- `getDopUslugiClassification(...)` — additional services
- `getTariffCategory(...)` — tariffs
- `getDeliveryScheme(...)` — delivery schemes
- `postReceiptCalculate(...)` — cost calculation (POST)
- `getClientCards()` *(auth)* — client cards
- `getClientInvoices()` *(auth)* — my invoices
- `getCargoCategory()` *(auth)* — cargo types
- `postCreateReceipts(receiptsList)` *(auth, POST)* — issue TTNs
- `getSenderList()` *(auth)* — my senders
- `postCreateAddressOrClient()` *(auth, POST)* — create address + client
- `getWarehousesListByCity(CityId, DirectionType)` — warehouses in city

**Coverage:** ~75% of basic TTN lifecycle (lookup → calculate → create → track).

### 1.2 Gaps (severity-ordered)

| # | Gap | Severity | Comment |
|---|---|---|---|
| 1 | Laravel 13 / PHP 8.5 support | 🔴 blocker | `^9.0\|^10.0` constraint can't install in modern consumer |
| 2 | `spatie/laravel-data` migration | 🟠 high | Custom DTO is dated; NP-API shows the canonical typed-property + `MapInputName` + `Wireable` pattern. Without `Wireable` consumers can't bind `wire:model` directly to the DTO inside Livewire forms |
| 3 | Real test coverage | 🟠 high | Only `ExampleTest::test_example()` placeholder. Need ≥10 unit tests with `Http::fake()` |
| 4 | `DeliveryAutoApiUnavailableException` | 🟠 high | Currently `RequestException` → `DeliveryException` without separating downtime vs application error. Need a subclass mirroring `NovaPoshtaApiUnavailableException` (NP-API 3.4.0) so consumers can route 5xx + cURL fails to a "warning" Bugsnag bucket |
| 5 | Status-code dictionary | 🟠 high | `getReceiptDetails` returns string status — needs an enum mapping (analog NP `Delivery::getState($statusCode)` → `DeliveryState` class) so consumers don't hardcode strings |
| 6 | README with real usage | 🟡 medium | Placeholder `echoPhrase('Hello')` example. Replicate NP-API README post-3.4.0 structure: install / config / quick start / per-module method tables / caching / enums / error-handling / Bugsnag routing snippet |
| 7 | CHANGELOG | 🟡 medium | `1.0.0 - 202X-XX-XX` placeholder |
| 8 | PHPStan / Pint | 🟡 medium | Static analysis + style enforcement absent |
| 9 | `delete` / `cancel` receipt | 🟡 medium | Currently only `POST` create — no API to cancel a mistakenly-created TTN. **Verify against PDF whether the upstream supports it.** |
| 10 | Print label URLs | 🟡 medium | NP has `Marking::printMarking()` for label / scan-sheet PDFs. **Check PDF v3.5.1 — does DA expose URL-based label download or only via cabinet UI?** |
| 11 | Batch tracking endpoint | 🟡 medium | `getReceiptDetails` is single — for an hourly cron with ~50 active deliveries that's 50 requests. **Check PDF — is there a batch variant?** If not, document the per-TTN polling cost |
| 12 | Webhooks | 🟡 medium | None known. Document explicitly. **Confirm with PDF v3.5.1.** |

---

## 2. Phases (~28 hours total, ~3.5 working days)

### Phase 0 — composer + tooling bump (~2 hours)

- Update `composer.json`:
  - `php: ^8.5` (or `^8.2` if the project must keep some headroom)
  - `illuminate/*: ^11.0||^12.0||^13.0`
  - `spatie/laravel-data: ^4.4`
  - `spatie/laravel-package-tools: ^1.16`
- Add dev dependencies:
  - `pestphp/pest: ^3.x`
  - `pestphp/pest-plugin-laravel`
  - `orchestra/testbench: ^9.0||^10.0`
  - `larastan/larastan` + `phpstan/phpstan`
  - `laravel/pint`
- Add CI skeleton (GitHub Actions: lint + test on PHP 8.2/8.3/8.5 + Laravel 11/12/13 matrix). Mirror NP-API.
- `phpstan.neon` + `pint.json` (or rely on Laravel preset).

### Phase 1 — DTO migration to `spatie/laravel-data` (~6 hours)

Rewrite every `*DataTransferObject` under `src/DataTransferObjects/` as a `Spatie\LaravelData\Data` subclass.

For each rewritten class:
- `#[MapInputName(StudlyCaseMapper::class)]` on the class (matches DA's PascalCase API field names like `WarehousesId`, `CityId`)
- Typed `public` properties with `?type` for optional fields
- `#[WithCast(EnumCast::class)]` for enum-shaped fields (status codes, country, etc.)
- `#[WithCast(CarbonInterfaceCast::class, format: 'd.m.Y H:i:s')]` for the various date formats DA returns (cross-check exact formats in PDF — `dateSend`, `arrivalDate`, etc.)
- Implement `Livewire\Wireable` on top-level DTOs that consumers will bind to `wire:model` (apply the same `toLivewire/fromLivewire` shape NP-API's `Money` patch shows)

Drop `BaseDataTransferObject::fromArray(...)` calls from `ApiModels/Warehouse.php` + `Receipt.php` and replace with `Data::collect(...)` / `Data::from(...)`.

Update `Request::make()` return type — return `array` instead of `Collection` so the data layer can construct typed DTOs upstream (NP-API does this). Or keep `Collection` and document that callers must `->toData()` themselves.

### Phase 2 — exception hierarchy (~2 hours)

Mirror NP-API 3.4.0's pattern exactly:

```php
// src/Exceptions/DeliveryAutoApiUnavailableException.php
class DeliveryAutoApiUnavailableException extends DeliveryException {}
```

Update `Request::make()`:
- catch `Illuminate\Http\Client\ConnectionException` → `DeliveryAutoApiUnavailableException`
- catch `RequestException` and split:
  - 5xx → `DeliveryAutoApiUnavailableException`
  - 4xx → `DeliveryException` (legacy behaviour preserved)
- application-level `status: false` payload → `DeliveryException`

Wrap with `previous: $e` so root cause survives in logs.

Add the same docstring guidance NP-API ships in its README + CHANGELOG (catch the subclass first when you want to react to downtime; route to Bugsnag at `warning` severity).

### Phase 3 — tests (~6 hours)

- Set up Pest + `Orchestra\Testbench` + `Http::fake()`
- Add at minimum:
  - `RequestTest` — HMAC hash composition, retry-on-5xx, `ConnectionException` → `DeliveryAutoApiUnavailableException`, application error path, cache-hit short-circuit
  - `WarehouseTest` — `getRegionList` happy + invalid `country` validation; `getWarehousesList` with `CityId` filter; `getWarehousesInfo` (invalid UUID rejected before HTTP); `getFindWarehouses` with coords
  - `ReceiptTest` — `getReceiptDetails` happy + 4xx + 5xx; `getDateArrival` happy + invalid date format; `postReceiptCalculate` POST shape; `postCreateReceipts` (auth + POST); `getSenderList` (auth header asserted)
  - `DataTransferObjectsTest` — round-trip assert for every DTO from a sample API payload (snapshot tests)
- Aim: ≥80% line coverage.

### Phase 4 — status code enum (~3 hours)

Receipt statuses from `getReceiptDetails`:
- Inspect PDF v3.5.1 chapter on receipt-status codes — list every state DA returns
- Build `Sashalenz\DeliveryAuto\Enums\ReceiptStatus`:
  - String-backed enum (`case Created = 'CREATED'`, etc.) matching upstream's labels
  - Helper `isFinal(): bool` (terminal states like Delivered / Cancelled / Returned)
  - Helper `isSuccess(): bool` (Delivered specifically)
- Replace string status access in `ReceiptDataTransferObject` with `public ReceiptStatus $status`
- Document in README: "consumers should not match raw strings — use the enum"

### Phase 5 — README + CHANGELOG + UPGRADING (~3 hours)

Mirror NP-API 3.4.0 README structure:
- Title + badges
- Requirements
- Installation + Configuration (`config/delivery-api.php` env vars)
- Per-counterparty / per-account credentials note (HMAC keys are global to the company — document the constraint)
- Quick start (warehouse lookup → calculate → create receipt round-trip)
- Per-module sections (Warehouse, Receipt) with method tables and a runnable example each
- `->cache($seconds)` pattern + cache-key composition note
- Enums cheat-sheet (`ReceiptStatus`, country IDs, culture)
- Error handling (with the new `DeliveryAutoApiUnavailableException` table + Bugsnag severity-routing snippet — copy NP-API's exact format for consistency)
- Testing / Changelog / Credits / License

CHANGELOG entries with Keep-a-Changelog format (Added / Changed / Fixed / Removed). Tag this work as `2.0.0` (breaking — DTO migration changes class shapes, exception name) or stage as `1.1.0` if backward shims are acceptable. Recommend: clean **2.0.0** + `UPGRADING.md` describing the DTO + exception shape changes for any pre-existing consumers (likely none yet — single-commit baseline).

### Phase 6 — missing methods per PDF spec (~6 hours)

**Audit PDF v3.5.1** for endpoints not yet in the package. Likely candidates to look for:
- Receipt cancel / delete (`PostDeleteReceipt`?)
- Receipt status history (vs single current status)
- Batch tracking — multi-TTN `getReceiptDetails` analog
- Print labels — URL builder for PDF stickers (mirror NP `Marking.php`)
- Address autocomplete by string (NP has `searchSettlements`)
- Sender CRUD (now we only `getSenderList` — can we create / update?)
- Webhooks registration (if any)
- Returns / readdress flow analog

For each found:
- Add to `ApiModels/Warehouse.php` or `ApiModels/Receipt.php` (or split into a new `ApiModels/Sender.php` etc. if the surface grows)
- Validation rules + DTO + test
- README method-table entry

### Phase 7 — release (~30 minutes)

- Tag `2.0.0`
- `git push origin main && git push origin 2.0.0`
- Satis rebuild on user infra
- Notify consumer (a20) — `composer update sashalenz/delivery-auto-api` after Satis confirms

---

## 3. Open questions to verify against `API_Delivery_v3_5_1.pdf`

These have to be answered **before Phase 6** so the missing-method audit is grounded.

1. **Multi-sender model** — `getSenderList` (auth) returns senders. Does upstream support multiple senders per company HMAC keypair, or one HMAC = one sender? Affects whether the SDK should expose a per-sender hint at the request layer.
2. **Currency unit** — `Sashalenz\DeliveryAuto\Delivery::CURRENCY = 100000000` (×10⁸). What is this — declared cargo value scale? Money amount divisor? PDF must clarify; rename + document precisely (avoid the "magic constant" trap).
3. **Cargo categories cache TTL** — `getCargoCategory` (auth) is dynamic. Document recommended cache duration (does PDF say how often it changes?).
4. **Invoice flow** — `getClientInvoices` returns what? Billing of cabinet services, or the receipt-as-invoice for a particular shipment? Knowing this clarifies whether it's a separate domain entity in the consumer's data model.
5. **Print labels** — Does DA expose URL-based label downloads (analogous to NP `Marking::printMarking()`)? Or labels only via cabinet UI? If the latter — document explicitly so consumers don't search for it.
6. **Batch tracking** — Is there a multi-TTN status method, or is per-TTN the only option? Drives polling architecture for downstream cron jobs.
7. **Webhooks** — Confirm none. Document the polling-only constraint in README so consumers plan accordingly.
8. **Returns / readdress** — How does the API support cancellation of a mistakenly-issued TTN, or a recipient change after dispatch? PDF will tell.

---

## 4. Reference patterns from `sashalenz/nova-poshta-api`

Copy-paste structural targets when writing equivalents in this package:

| What to mirror | NP-API path |
|---|---|
| Composer.json shape (modern Laravel matrix + dev tooling) | [composer.json](https://github.com/sashalenz/nova-poshta-api/blob/main/composer.json) |
| `Request.php` retry + exception split | [src/Request.php](https://github.com/sashalenz/nova-poshta-api/blob/main/src/Request.php) |
| `BaseModel` fluent builder | [src/ApiModels/BaseModel.php](https://github.com/sashalenz/nova-poshta-api/blob/main/src/ApiModels/BaseModel.php) |
| Exception hierarchy + Unavailable subclass | [src/Exceptions/](https://github.com/sashalenz/nova-poshta-api/tree/main/src/Exceptions) |
| Status code → state class mapper | [src/Enums/](https://github.com/sashalenz/nova-poshta-api/tree/main/src/Enums) |
| `Spatie\LaravelData` request/response shapes with `MapInputName(StudlyCaseMapper::class)` | [src/ApiModels/*/RequestData/](https://github.com/sashalenz/nova-poshta-api/tree/main/src/ApiModels) |
| `Wireable` implementation example | upstream Money patch (`patches/moneyphp-money-non-final-wireable.patch` in the consumer repo) |
| README structure (post-3.4.0 docs commit) | [README.md](https://github.com/sashalenz/nova-poshta-api/blob/main/README.md) |
| CHANGELOG style (Keep-a-Changelog) | [CHANGELOG.md](https://github.com/sashalenz/nova-poshta-api/blob/main/CHANGELOG.md) |

---

## 5. Out of scope for this sprint

- **a20 consumer module** (`Services/DeliveryServices/DeliveryAuto/`) — separate work, ~5-6 days, blocks on this package being tagged. See [`docs/tasks/delivery-auto-service.md`](https://github.com/a20ua/a20-manager-laravel/blob/main/docs/tasks/delivery-auto-service.md) in the consumer repo for the full a20-side breakdown.
- **Production rollout / business onboarding** — once consumer ships, real API keys go to dev first, then a manual smoke with real cargo creation, then training of пакувальники.
- **Multi-language support** beyond UA/RU `culture` param (already supported by upstream).

---

## 6. Definition of done

- All 12 gap items from §1.2 closed
- ≥10 tests passing locally + in CI
- PHPStan level 6+ clean
- Pint clean
- README quick-start example actually runs
- CHANGELOG `[2.0.0] - YYYY-MM-DD` block populated
- Tag `2.0.0` pushed, Satis confirms availability
