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

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
    'brandfetch' => [
        'key' => env('BRANDFETCH_API_KEY'),
    ],

    'gemini' => [
        'key' => env('GEMINI_API_KEY'),
    ],

    'ghn' => [
        'url'     => env('GHN_API_URL', 'https://online-gateway.ghn.vn/shiip/public-api'),
        'token'   => env('GHN_TOKEN'),
        'shop_id' => env('GHN_SHOP_ID'),
    ],

    'sepay' => [
        'base_url'     => env('SEPAY_BASE_URL', 'https://pgapi-sandbox.sepay.vn'),
        'secret_key'   => env('SEPAY_SECRET_KEY'),
        'bank_account' => env('SEPAY_BANK_ACCOUNT'),
        'bank_name'    => env('SEPAY_BANK_NAME', 'MBBank'),
    ],

];

