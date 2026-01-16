<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'secret_key' => env('247TRAVELS_SECRET_KEY', 'otsk_sk_test_Clhv2UHvJZSrOPBEMpMATyGFZJmMSlcPC2OJVR2R7OSo'),

    'public_key' => env('247TRAVELS_PUBLIC_KEY', ''),

    'environment' => env('247TRAVELS_ENVIRONMENT', 'test'),

    'test_url' => env('247TRAVELS_TEST_URL', 'https://test.ota.api.247travels.com/api'),

    'live_url' => env('247TRAVELS_LIVE_URL','https://ota.api.247travels.com/api'),

    

];
