<?php

return [
    'url' => env('DELIVERY_API_URL', 'https://delivery-auto.com/api/v4/Public'),

    'public_key' => env('DELIVERY_API_PUBLIC_KEY', null),
    'secret_key' => env('DELIVERY_API_SECRET_KEY', null),

    'culture' => env('DELIVERY_API_CULTURE', 'ru-RU')
];
