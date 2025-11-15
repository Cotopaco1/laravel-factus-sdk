<?php

// config for Cotopaco/Factus
return [
    'base_url' => env('FACTUS_BASE_URL', 'https://api.factus.com.co'),
    'sandbox_base_url' => env('FACTUS_SANDBOX_BASE_URL', 'https://api-sandbox.factus.com.co'),
    'client' => [
        'id' => env('FACTUS_CLIENT_ID'),
        'secret' => env('FACTUS_CLIENT_SECRET')
    ],
    'username' => env('FACTUS_USERNAME'),
    'password' => env('FACTUS_PASSWORD'),
    'production' => env('FACTUS_PRODUCTION', false)
];
