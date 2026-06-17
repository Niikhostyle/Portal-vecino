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

    'claveunica' => [
        'client_id' => env('CLAVEUNICA_CLIENT_ID'),
        'client_secret' => env('CLAVEUNICA_CLIENT_SECRET'),
        'redirect' => env('CLAVEUNICA_REDIRECT_URI'),
        'end_session_url' => 'https://accounts.claveunica.gob.cl/openid/end-session',
    ],

    'chanco' => [
        'feed_url' => env('CHANCO_FEED_URL', 'https://chanco.cl/feed/'),
        'wp_api_url' => env('CHANCO_WP_API_URL', 'https://chanco.cl/wp-json/wp/v2'),
        'noticias_url' => env('CHANCO_NOTICIAS_URL', 'https://chanco.cl'),
        'cache_ttl' => env('CHANCO_NOTICIAS_CACHE_TTL', 3600),
    ],

];
