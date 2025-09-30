<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains security-related configuration options for the
    | application.
    |
    */

    'rate_limiting' => [
        'auth_attempts' => 5, // Количество попыток входа
        'auth_decay_minutes' => 1, // Время блокировки в минутах
        'api_requests' => 100, // Количество API запросов
        'api_decay_minutes' => 1, // Время для API лимита
        'article_creation_attempts' => 3, // Количество статей в минуту
        'article_creation_decay_minutes' => 1, // Время для лимита статей
        'comment_creation_attempts' => 1, // Количество комментариев за период
        'comment_creation_decay_seconds' => 5, // Время между комментариями в секундах
    ],

    'password' => [
        'min_length' => 8,
        'require_uppercase' => true,
        'require_lowercase' => true,
        'require_numbers' => true,
        'require_symbols' => false,
    ],

    'session' => [
        'lifetime' => 120, // Время жизни сессии в минутах
        'secure' => env('SESSION_SECURE_COOKIE', false),
        'http_only' => true,
        'same_site' => 'lax',
    ],

    'cors' => [
        'allowed_origins' => explode(',', env('CORS_ALLOWED_ORIGINS', 'http://localhost:3000')),
        'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
        'allowed_headers' => ['Content-Type', 'Authorization', 'X-Requested-With'],
        'max_age' => 86400, // 24 часа
    ],
];
