<?php

$isLive = filter_var(env('247TRAVELS_IS_LIVE', false), FILTER_VALIDATE_BOOLEAN);

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

    'is_live' => $isLive,

    'username' => $isLive ? env('247TRAVELS_LIVE_USERNAME') : env('247TRAVELS_USERNAME'),

    'password' => $isLive ? env('247TRAVELS_LIVE_PASSWORD') : env('247TRAVELS_PASSWORD'),

    'base_url' => $isLive ? env('247TRAVELS_LIVE_BASE_URL') : env('247TRAVELS_BASE_URL', 'https://247travels.cloud'),

    'token_cache_key' => 'skylink_access_token',
    'refresh_token_cache_key' => 'skylink_refresh_token',
    'token_expiry_cache_key' => 'skylink_token_expires_at',

];
