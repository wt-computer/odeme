<?php
// config/iyzico.php
return [
    'api_key' => env('IYZICO_API_KEY', ''),
    'secret_key' => env('IYZICO_SECRET_KEY', ''),
    'base_url' => env('IYZICO_BASE_URL', 'https://sandbox-api.iyzipay.com'), // Sandbox ortamı için
    // 'base_url' => env('IYZICO_BASE_URL', 'https://api.iyzipay.com'), // Canlı ortam için
];
