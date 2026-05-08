<?php

declare(strict_types=1);

use Sashalenz\DeliveryAuto\Enums\Culture;
use Sashalenz\DeliveryAuto\SendingRegisterUrlBuilder;

it('builds SendingRegister URL with id, type, and culture per PDF §6.18', function () {
    $url = SendingRegisterUrlBuilder::url(
        id: '00123456',
        type: SendingRegisterUrlBuilder::FORM_TYPE_DETAILED,
        culture: Culture::UkUA,
    );

    expect($url)->toBe('https://www.delivery-auto.com/uk-UA/SharedForms/SendingRegister?id=00123456&type=0');
});

it('defaults to type=1 (summary) and uk-UA culture', function () {
    $url = SendingRegisterUrlBuilder::url('99999');

    expect($url)->toContain('type=1')
        ->and($url)->toContain('/uk-UA/');
});
