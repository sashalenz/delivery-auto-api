<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use Sashalenz\DeliveryAuto\ApiModels\Calculation\RequestData\CategoryRequest;
use Sashalenz\DeliveryAuto\ApiModels\Receipt\RequestData\GetPdfDocumentRequest;
use Sashalenz\DeliveryAuto\ApiModels\Receipt\RequestData\GetSenderListRequest;
use Sashalenz\DeliveryAuto\ApiModels\Receipt\RequestData\PostCreateReceiptsRequest;
use Sashalenz\DeliveryAuto\ApiModels\Receipt\RequestData\RegistrationReceiptRequest;
use Sashalenz\DeliveryAuto\ApiModels\Receipt\ResponseData\PdfDocumentData;
use Sashalenz\DeliveryAuto\DeliveryAuto;
use Sashalenz\DeliveryAuto\Enums\DeliveryScheme;
use Sashalenz\DeliveryAuto\Enums\DocumentType;
use Spatie\LaravelData\DataCollection;

it('GetSenderList sends HMAC auth header', function () {
    Http::fake([
        '*GetSenderList*' => Http::response([
            'status' => true,
            'data' => [
                ['id' => 'cdbfe2d5-bf02-4c0d-b7d6-5cf277761c50', 'name' => 'Test Sender', 'cityId' => '16617DF3-A42A-E311-8B0D-00155D037960', 'cityName' => 'Київ'],
            ],
        ]),
    ]);

    $senders = DeliveryAuto::receipt()->getSenderList(new GetSenderListRequest);

    expect($senders)->toHaveCount(1);
    Http::assertSent(fn ($req) => str_starts_with(
        $req->header('HMACAuthorization')[0] ?? '',
        'amx CDBFE2D5-BF02-4C0D-B7D6-5CF277761C50:'
    ));
});

it('PostCreateReceipts uses receipts data-key and HMAC auth', function () {
    Http::fake([
        '*PostCreateReceipts*' => Http::response([
            'status' => true,
            'message' => [],
            'receipts' => [
                [
                    'Id' => 'f5a947f6-adcf-49e8-be46-a49d69621ae2',
                    'Number' => '9900000000',
                    'TotallCost' => 97.0,
                    'InsuranceCost' => 7.0,
                    'ComissionGM' => 30.0,
                    'ReceiveDate' => '01.01.2024 14:30:00',
                    'Comment' => '',
                    'egs' => [['Id' => '3fed9940-b094-4236-a3b0-728a83123eca', 'PartnerNumber' => null, 'Number' => '9900000000002002151']],
                ],
            ],
        ]),
    ]);

    $created = DeliveryAuto::receipt()->postCreateReceipts(new PostCreateReceiptsRequest(
        receiptsList: new DataCollection(RegistrationReceiptRequest::class, [
            new RegistrationReceiptRequest(
                areasSendId: 'f6ee49fa-3e29-e311-8b0d-00155d037960',
                areasResiveId: 'ebc7639a-db2a-e311-8b0d-00155d037960',
                dateSend: new DateTime('2024-01-01'),
                category: new DataCollection(CategoryRequest::class, [
                    ['categoryId' => '00000000-0000-0000-0000-000000000000', 'countPlace' => 1, 'helf' => 1, 'size' => 1],
                ]),
                deliveryScheme: DeliveryScheme::WarehouseWarehouse,
                warehouseSendId: '6b3b6d45-b249-e211-ab75-00155d012d0d',
                warehouseResiveId: 'ab3b6d45-b249-e211-ab75-00155d012d0d',
                receiverName: 'Іванков Іван Іванович',
                receiverPhone: '0500000000',
            ),
        ]),
    ));

    expect($created)->toHaveCount(1)
        ->and($created[0]->Number)->toBe('9900000000')
        ->and($created[0]->TotallCost)->toBe(97.0)
        ->and($created[0]->ReceiveDate?->format('Y-m-d H:i:s'))->toBe('2024-01-01 14:30:00');

    Http::assertSent(fn ($req) => $req->method() === 'POST'
        && str_contains($req->url(), 'PostCreateReceipts')
        && ! empty($req->header('HMACAuthorization'))
    );
});

it('GetPdfDocument decodes base64 binary correctly', function () {
    $pdfBytes = "%PDF-1.4\n%fake".str_repeat('A', 100);

    Http::fake([
        '*GetPdfDocument*' => Http::response([
            'status' => true,
            'message' => '',
            'file' => base64_encode($pdfBytes),
        ]),
    ]);

    $pdf = DeliveryAuto::receipt()->getPdfDocument(new GetPdfDocumentRequest(
        number: ['9900112233', '9900223344'],
        type: DocumentType::StickersOneSheet,
    ));

    expect($pdf)->toBeInstanceOf(PdfDocumentData::class)
        ->and($pdf->binary())->toBe($pdfBytes);

    Http::assertSent(fn ($req) => str_contains($req->url(), 'number=9900112233%3B9900223344')
        && str_contains($req->url(), 'type=2')
    );
});
