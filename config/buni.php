<?php

return [
    /*
    |------------------------------------------------------
    | Set sandbox mode
    | ------------------------------------------------------
    | Specify whether this is a test app or production app
    |
    | Sandbox base url: https://uat.buni.kcbgroup.com
    | Production base url: todo()
    */
    'sandbox' => env('BUNI_SANDBOX', false),

    /*
    |--------------------------------------------------------------------------
    | Cache credentials/keys
    |--------------------------------------------------------------------------
    |
    | If you decide to cache credentials, they will be kept in your app cache
    | configuration for some time. Reducing the need for many requests for
    | generating credentials/encryption keys
    |
    */
    'cache_credentials' => true,

    /*
    |--------------------------------------------------------------------------
    | URL
    |--------------------------------------------------------------------------
    |
    | Url of the api
    |
    */
    'url' => env('BUNI_URL', 'https://uat.buni.kcbgroup.com'),


    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    |
    | Whether to log in the library
    |
    */
    'logging' => [
        'enabled' => env('BUNI_ENABLE_LOGGING', false),
        'channels' => [
            'single', 'stderr',
        ],
    ],
];
