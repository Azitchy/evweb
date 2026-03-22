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

    'esewa' => [
        'merchant_id' => env('ESEWA_MERCHANT_ID', ''),
        'secret_key' => env('ESEWA_SECRET_KEY', ''),
        'base_url' => env('ESEWA_BASE_URL', 'https://rc-epay.esewa.com.np'),
        'success_url' => env('ESEWA_SUCCESS_URL', ''),
        'failure_url' => env('ESEWA_FAILURE_URL', ''),
    ],

    'khalti' => [
        'secret_key' => env('KHALTI_SECRET_KEY', ''),
        'base_url' => env('KHALTI_BASE_URL', 'https://a.khalti.com'),
        'return_url' => env('KHALTI_RETURN_URL', ''),
        'website_url' => env('KHALTI_WEBSITE_URL', ''),
    ],

    'fcm' => [
        'server_key' => env('FCM_SERVER_KEY', ''),
    ],

];
