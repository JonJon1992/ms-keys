<?php

use Core\Utils\Path;
use Monolog\Logger;

return [
    'default' => 'main',
    'main' => [
        'path' => Path::logs() . '/main.log',
        'level' => Logger::DEBUG,
    ],
    'ms-keys' => [
        'path' => Path::logs() . '/ms-keys.log',
        'level' => Logger::DEBUG
    ]
];