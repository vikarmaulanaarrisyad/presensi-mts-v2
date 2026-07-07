<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | Di sini Anda menentukan "guard" dan "password broker" default untuk
    | aplikasi Anda. Anda bisa mengubahnya jika ingin, tetapi untuk sistem
    | multi-login, kita akan menentukan guard secara spesifik nanti.
    |
    */

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Di sini Anda menentukan setiap guard autentikasi untuk aplikasi Anda.
    | Kita menambahkan guard 'siswa' dan 'admin' yang menggunakan driver 'session'.
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        
        // Tambahan Guard untuk Siswa
        'siswa' => [
            'driver' => 'session',
            'provider' => 'siswas',
        ],

        // Tambahan Guard untuk Admin (Baru)
        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | Di sini kita menentukan bagaimana user diambil dari database.
    | Kita arahkan 'siswas' untuk Model Siswa dan 'admins' untuk Model Admin.
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        // Tambahan Provider untuk Tabel Siswa
        'siswas' => [
            'driver' => 'eloquent',
            'model' => App\Models\Siswa::class,
        ],

        // Tambahan Provider untuk Tabel Admin (Baru)
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | Konfigurasi jika user lupa password.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
        'siswas' => [
            'provider' => 'siswas',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
        'admins' => [
            'provider' => 'admins',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    */

    'password_timeout' => 10800,

];