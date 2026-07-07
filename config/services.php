<?php

return [

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => env('SES_REGION', 'us-east-1'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Konfigurasi WhatsApp & Telegram (Custom)
    |--------------------------------------------------------------------------
    | Baris di bawah ini kita buat sendiri agar bisa dipanggil di Controller
    */

    'telegram' => [
        'bot_token' => env('TELEGRAM_BOT_TOKEN'),
    ],

    'fonnte' => [
        'api_key' => env('FONNTE_API_KEY'), // Ini adalah bagian untuk WhatsApp (WA)
    ],

];