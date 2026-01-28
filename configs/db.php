<?php

return [
    'default' => "ms-keys",
    "ms-keys" => [
        "driver" => "pgsql",
        "host" => env("DB_HOST", "localhost"),
        "port" => env("DB_PORT", 5432),
        'username_master' => env('DB_USERNAME_MASTER', 'postgres'),
        'password_master' => env('DB_PASSWORD_MASTER'),
        "database" => env("DB_DATABASE", "postgres"),
        "username" => env("DB_USERNAME", "postgres"),
        "password" => env("DB_PASSWORD", "postgres"),
    ]
];
