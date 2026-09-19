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

'google' => [
    'client_id'     => env('GOOGLE_CLIENT_ID'),
    'translate_key' => env('GOOGLE_TRANSLATE_KEY'),
],

'facebook' => [
    'app_id' => env('FACEBOOK_APP_ID'),
    'app_secret' => env('FACEBOOK_APP_SECRET'),
],

'mono' => [
    'secret_key' => env('MONO_SECRET_KEY'),
],

'agora' => [
    'app_id'          => env('AGORA_APP_ID'),
    'app_certificate' => env('AGORA_APP_CERTIFICATE'),
],

'paystack' => [
    'public_key' => env('PAYSTACK_PUBLIC_KEY'),
    'secret_key' => env('PAYSTACK_SECRET_KEY'),
    'secret'     => env('PAYSTACK_SECRET_KEY'), // Add this line as a fallback string mapping!
    'url'        => env('PAYSTACK_PAYMENT_PAGE_URL', 'https://paystack.shop/pay/sbraisolutionsltd'),
],

'espees' => [
    'env' => env('ESPEES_ENV', 'sandbox'),
    // Real API root, confirmed from developers.espees.org's own PHP sample
    // (`https://api.espees.org/v2/payment/product`) — not the same as
    // ESPEES_BASE_URL previously had, which pointed at a URL that doesn't
    // match their documented API at all.
    'base_url' => env('ESPEES_BASE_URL', 'https://api.espees.org/v2'),
    // Separate domain — this is the hosted checkout page users are
    // redirected to after we create a payment product, NOT an API host.
    'payment_portal_url' => env('ESPEES_PAYMENT_PORTAL_URL', 'https://payment.espees.org/pay'),
    'api_key' => env('ESPEES_API_KEY'),
    // The real payment API only requires x-api-key (confirmed from their
    // docs) — no secret key. Left here in case webhook signature
    // verification needs it later, but the service no longer sends it.
    'secret_key' => env('ESPEES_SECRET_KEY'),
    'merchant_wallet' => env('ESPEES_MERCHANT_ADDRESS'),
    'webhook_secret' => env('ESPEES_WEBHOOK_SECRET'),
],

'termii' => [
    'api_key'   => env('TERMII_API_KEY'),
    'sender_id' => env('TERMII_SENDER_ID', 'Sbrai'),
    'base_url' => env('TERMII_BASE_URL', 'https://v4.api.termii.com'),
],

'firebase' => [
    // Path to the Firebase service-account JSON, relative to the app
    // root — e.g. FIREBASE_CREDENTIALS=storage/app/firebase/sbrai-solutions-5ad4b-firebase-adminsdk-fbsvc-1bcf79defe.json
    // Kept outside git (see .gitignore) since it's a live credential,
    // same sensitivity as the Paystack secret key.
    'credentials' => env('FIREBASE_CREDENTIALS') ? base_path(env('FIREBASE_CREDENTIALS')) : null,
    'project_id'  => env('FIREBASE_PROJECT_ID'),
],

];
