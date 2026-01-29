<?php

return [
    'default' => "ms-keys",
    "ms-keys" => [
        "driver" => "redis",
        "host" => env("REDIS_HOST", "localhost"),
        "port" => env("REDIS_PORT", 6379),
        "password" => env("REDIS_PASSWORD", ""),
        "timeout" => env("REDIS_TIMEOUT", 2),
        "scheme" => env("REDIS_SCHEME", "tcp"),
    ]

];
