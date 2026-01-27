<?php

use Core\Utils\Path;
use MSKeys\Module as MSKeys;

return [
    'error' => (bool)env('APP_ERROR', true),
    'details' => (bool)env('APP_ERROR_DETAILS', true),
    'debug' => (bool)env('APP_DEBUG', false),
    'log_level' => empty($level = env('APP_LOG_LEVEL', 'DEBUG')) ? 'DEBUG' : $level,
    'modules' => [
        MSKeys::class,
    ],
    'view' => [
        'folder' => Path::views()
    ]
];
