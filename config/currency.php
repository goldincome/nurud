<?php


return [

    /*
    |--------------------------------------------------------------------------
    | Currency Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains settings related to currency handling in the application.
    | You can specify the default currency, supported currencies, and exchange rate API settings.
    |
    */

    'default_currency' => env('DEFAULT_CURRENCY', 'GBP'),

    'supported_currencies' => [
        'usd' =>['code'=>'USD', 'symbol'=>'$'], 
        'eur' =>['code'=>'EUR', 'symbol'=>'€'], 
        'gbp' =>['code'=>'GBP', 'symbol'=>'£'], 
        'ngn' =>['code'=>'NGN', 'symbol'=>'₦']
    
    ],


];