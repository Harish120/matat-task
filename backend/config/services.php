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
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'woocommerce' => [
        'api_url' => env('WOOCOMMERCE_API_URL', "https://interview-test.matat.io/wp-json/wc/v3/"),
        'consumer_key' => env('WOOCOMMERCE_CONSUMER_KEY', 'ck_40d0806b16feb3bd67a4d8dbbff163c6dfcf061d'),
        'consumer_secret' => env('WOOCOMMERCE_CONSUMER_SECRET', 'cs_9544e30809595750f8f1c6f3f9a6efcc38bfd06d'),
    ],

];
