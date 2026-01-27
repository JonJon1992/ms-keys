<?php

return [
    'default' => "ms-keys",
    "ms-keys" => [
        "driver" => "pgsql",
        "host" => env("DB_HOST", "localhost"),
        "port" => env("DB_PORT", 5432),
        "database" => env("DB_DATABASE", "postgres"),
        "username" => env("DB_USERNAME", "postgres"),
        "password" => env("DB_PASSWORD", "postgres"),
    ]   
];