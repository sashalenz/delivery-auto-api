<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | API base URL
    |--------------------------------------------------------------------------
    |
    | Endpoint root for the JSON API per PDF v3.5.1. SendingRegister (§6.18)
    | lives at a separate URL — see `shared_forms_url` below.
    */
    'url' => env('DELIVERY_API_URL', 'https://www.delivery-auto.com/api/v4/Public/'),

    /*
    |--------------------------------------------------------------------------
    | HMAC API keys (§6.1)
    |--------------------------------------------------------------------------
    |
    | Issued per company by Delivery-Auto. Required for all §6 endpoints.
    | Keep them out of source control — set via .env.
    */
    'public_key' => env('DELIVERY_API_PUBLIC_KEY'),
    'secret_key' => env('DELIVERY_API_SECRET_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Cabinet login credentials (§5, §7)
    |--------------------------------------------------------------------------
    |
    | OPTIONAL. Only needed if the consumer wants to call cabinet/log endpoints
    | via Delivery::cabinet()->loginFromConfig(). Each PHP process holds a single
    | session — multi-tenant apps should call ->postLogin(...) per request with
    | per-tenant credentials instead.
    */
    'username' => env('DELIVERY_API_USERNAME'),
    'password' => env('DELIVERY_API_PASSWORD'),

    /*
    |--------------------------------------------------------------------------
    | SendingRegister (§6.18) URL prefix
    |--------------------------------------------------------------------------
    */
    'shared_forms_url' => env('DELIVERY_API_SHARED_FORMS_URL', 'https://www.delivery-auto.com/'),
];
