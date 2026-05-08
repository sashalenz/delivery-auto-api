<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use Sashalenz\DeliveryAuto\ApiModels\Communication\RequestData\GetMessagesThemeRequest;
use Sashalenz\DeliveryAuto\ApiModels\Communication\RequestData\GetNewsRequest;
use Sashalenz\DeliveryAuto\DeliveryAuto;

it('parses paginated GetNews into NewsItemData collection', function () {
    Http::fake([
        '*GetNews*' => Http::response([
            'status' => true,
            'data' => [
                [
                    'NewsItemId' => 133905,
                    'Title' => 'Відновлення роботи представництва у м. Рогатин',
                    'ShortContent' => 'Відновлення роботи',
                    'Content' => '<p>...</p>',
                    'PublishDate' => '2024-03-13T00:00:00',
                    'ImageName' => null,
                    'ImageUrl' => null,
                    'ImageContent' => '',
                    'WarehousesId' => null,
                ],
            ],
        ]),
    ]);

    $news = DeliveryAuto::communication()->getNews(new GetNewsRequest(count: 5, page: 1));

    expect($news)->toHaveCount(1)
        ->and($news[0]->NewsItemId)->toBe(133905)
        ->and($news[0]->Title)->toContain('Рогатин');
});

it('parses GetMessagesTheme', function () {
    Http::fake([
        '*GetMessagesTheme*' => Http::response([
            'status' => true,
            'data' => [
                ['Id' => 'AGREEMENT', 'Name' => 'Укладення договору'],
                ['Id' => 'CARGO_DAMAGE', 'Name' => 'Затримка, втрата, пошкодження вантажу'],
            ],
        ]),
    ]);

    $themes = DeliveryAuto::communication()->getMessagesTheme(new GetMessagesThemeRequest);

    expect($themes)->toHaveCount(2)
        ->and($themes[0]->Id)->toBe('AGREEMENT');
});
