<?php

return [

    /*
    |--------------------------------------------------------------------------
    | SkyLink API (247Travels) Configuration
    |--------------------------------------------------------------------------
    |
    | This file stores credentials and configuration for the SkyLink API
    | that replaces the legacy FlexiAPI.
    |
    */

    'username' => env('247TRAVELS_USERNAME'),

    'password' => env('247TRAVELS_PASSWORD'),

    'base_url' => env('247TRAVELS_BASE_URL', 'https://247travels.cloud'),

    'token_cache_key' => 'skylink_access_token',
    'refresh_token_cache_key' => 'skylink_refresh_token',
    'token_expiry_cache_key' => 'skylink_token_expires_at',

];
