<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Receipt;

use Sashalenz\DeliveryAuto\ApiModels\BaseModel;
use Sashalenz\DeliveryAuto\ApiModels\Receipt\RequestData\GetAvailableServicesRequest;
use Sashalenz\DeliveryAuto\ApiModels\Receipt\RequestData\GetClientAddressRequest;
use Sashalenz\DeliveryAuto\ApiModels\Receipt\RequestData\GetClientCardsRequest;
use Sashalenz\DeliveryAuto\ApiModels\Receipt\RequestData\GetClientInvoicesRequest;
use Sashalenz\DeliveryAuto\ApiModels\Receipt\RequestData\GetClientPaymentTypeRequest;
use Sashalenz\DeliveryAuto\ApiModels\Receipt\RequestData\GetCurrencyRequest;
use Sashalenz\DeliveryAuto\ApiModels\Receipt\RequestData\GetPayerRequest;
use Sashalenz\DeliveryAuto\ApiModels\Receipt\RequestData\GetPdfDocumentRequest;
use Sashalenz\DeliveryAuto\ApiModels\Receipt\RequestData\GetPosibleReciverRequest;
use Sashalenz\DeliveryAuto\ApiModels\Receipt\RequestData\GetSenderListRequest;
use Sashalenz\DeliveryAuto\ApiModels\Receipt\RequestData\PostAddReceiptIntoPickUpRequest;
use Sashalenz\DeliveryAuto\ApiModels\Receipt\RequestData\PostCreateAddressOrClientRequest;
use Sashalenz\DeliveryAuto\ApiModels\Receipt\RequestData\PostCreateReceiptsRequest;
use Sashalenz\DeliveryAuto\ApiModels\Receipt\RequestData\PostDeactivateEgRequest;
use Sashalenz\DeliveryAuto\ApiModels\Receipt\ResponseData\AvailableServicesData;
use Sashalenz\DeliveryAuto\ApiModels\Receipt\ResponseData\ClientCardData;
use Sashalenz\DeliveryAuto\ApiModels\Receipt\ResponseData\ClientInvoiceData;
use Sashalenz\DeliveryAuto\ApiModels\Receipt\ResponseData\CreatedAddressOrClientData;
use Sashalenz\DeliveryAuto\ApiModels\Receipt\ResponseData\CreatedReceiptData;
use Sashalenz\DeliveryAuto\ApiModels\Receipt\ResponseData\PdfDocumentData;
use Sashalenz\DeliveryAuto\ApiModels\Receipt\ResponseData\SenderData;
use Sashalenz\DeliveryAuto\ApiModels\Receipt\ResponseData\SimpleNamedItemData;
use Sashalenz\DeliveryAuto\Exceptions\DeliveryAutoException;
use Sashalenz\DeliveryAuto\Request;
use Spatie\LaravelData\DataCollection;

/**
 * §6 Оформлення квитанції — HMAC-authed receipt issuance + lookups.
 */
final class Receipt extends BaseModel
{
    /**
     * @return DataCollection<int, ClientCardData>
     *
     * @throws DeliveryAutoException
     */
    public function getClientCards(?GetClientCardsRequest $request = null): DataCollection
    {
        return $this
            ->reset()
            ->method('GetClientCards')
            ->params($request ?? new GetClientCardsRequest)
            ->withHmac()
            ->toCollection(ClientCardData::class);
    }

    /**
     * @return DataCollection<int, ClientInvoiceData>
     *
     * @throws DeliveryAutoException
     */
    public function getClientInvoices(?GetClientInvoicesRequest $request = null): DataCollection
    {
        return $this
            ->reset()
            ->method('GetClientInvoices')
            ->params($request ?? new GetClientInvoicesRequest)
            ->withHmac()
            ->toCollection(ClientInvoiceData::class);
    }

    /**
     * @return DataCollection<int, CreatedReceiptData>
     *
     * @throws DeliveryAutoException
     */
    public function postCreateReceipts(PostCreateReceiptsRequest $request): DataCollection
    {
        return $this
            ->reset()
            ->method('PostCreateReceipts')
            ->params($request)
            ->post()
            ->withHmac()
            ->dataKey('receipts')
            ->toCollection(CreatedReceiptData::class);
    }

    /**
     * §6.5 Deactivate cargo units.
     *
     * @throws DeliveryAutoException
     */
    public function postDeactivateEg(PostDeactivateEgRequest $request): bool
    {
        $this
            ->reset()
            ->method('PostDeactivateEg')
            ->params($request)
            ->post()
            ->withHmac()
            ->get();

        return true;
    }

    /**
     * §6.6 Get a TTN PDF document. Response field `file` is base64-encoded.
     *
     * @throws DeliveryAutoException
     */
    public function getPdfDocument(GetPdfDocumentRequest $request): ?PdfDocumentData
    {
        $payload = $this
            ->reset()
            ->method('GetPdfDocument')
            ->params($request)
            ->withHmac()
            ->dataKey(Request::DATA_KEY_ROOT)
            ->get();

        if ($payload->isEmpty() || ! $payload->has('file')) {
            return null;
        }

        return PdfDocumentData::from($payload->all());
    }

    /**
     * @return DataCollection<int, SenderData>
     *
     * @throws DeliveryAutoException
     */
    public function getSenderList(?GetSenderListRequest $request = null): DataCollection
    {
        return $this
            ->reset()
            ->method('GetSenderList')
            ->params($request ?? new GetSenderListRequest)
            ->withHmac()
            ->toCollection(SenderData::class);
    }

    /**
     * @return DataCollection<int, SimpleNamedItemData>
     *
     * @throws DeliveryAutoException
     */
    public function getCurrency(GetCurrencyRequest $request): DataCollection
    {
        return $this
            ->reset()
            ->method('GetCurrency')
            ->params($request)
            ->toCollection(SimpleNamedItemData::class);
    }

    /**
     * @throws DeliveryAutoException
     */
    public function getAvailableServices(GetAvailableServicesRequest $request): ?AvailableServicesData
    {
        /** @var AvailableServicesData|null */
        return $this
            ->reset()
            ->method('GetAvailableServices')
            ->params($request)
            ->toData(AvailableServicesData::class);
    }

    /**
     * @return DataCollection<int, SimpleNamedItemData>
     *
     * @throws DeliveryAutoException
     */
    public function getPayer(GetPayerRequest $request): DataCollection
    {
        return $this
            ->reset()
            ->method('GetPayer')
            ->params($request)
            ->withHmac()
            ->toCollection(SimpleNamedItemData::class);
    }

    /**
     * @return DataCollection<int, SimpleNamedItemData>
     *
     * @throws DeliveryAutoException
     */
    public function getClientAddress(GetClientAddressRequest $request): DataCollection
    {
        return $this
            ->reset()
            ->method('GetClientAddress')
            ->params($request)
            ->toCollection(SimpleNamedItemData::class);
    }

    /**
     * @return DataCollection<int, SimpleNamedItemData>
     *
     * @throws DeliveryAutoException
     */
    public function getPosibleReciver(GetPosibleReciverRequest $request): DataCollection
    {
        return $this
            ->reset()
            ->method('GetPosibleReciver')
            ->params($request)
            ->withHmac()
            ->toCollection(SimpleNamedItemData::class);
    }

    /**
     * §6.13 — returns a single boolean payload (cash vs cashless).
     *
     * @throws DeliveryAutoException
     */
    public function getClientPaymentType(GetClientPaymentTypeRequest $request): bool
    {
        $payload = $this
            ->reset()
            ->method('GetClientPaymentType')
            ->params($request)
            ->get();

        return (bool) $payload->first();
    }

    /**
     * §6.15 PostCreateAddressOrClient — returns either an address, an account, or both.
     *
     * @throws DeliveryAutoException
     */
    public function postCreateAddressOrClient(PostCreateAddressOrClientRequest $request): ?CreatedAddressOrClientData
    {
        /** @var CreatedAddressOrClientData|null */
        return $this
            ->reset()
            ->method('PostCreateAddressOrClient')
            ->params($request)
            ->post()
            ->withHmac()
            ->toData(CreatedAddressOrClientData::class);
    }

    /**
     * §6.17 PostAddReceiptIntoPickUpRequest — combine TTNs into a single pickup order.
     *
     * @throws DeliveryAutoException
     */
    public function postAddReceiptIntoPickUpRequest(PostAddReceiptIntoPickUpRequest $request): bool
    {
        $this
            ->reset()
            ->method('PostAddReceiptIntoPickUpRequest')
            ->params($request)
            ->post()
            ->withHmac()
            ->get();

        return true;
    }
}
