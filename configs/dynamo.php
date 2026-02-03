<?php

return [
    'default' => 'ms-keys',
    'ms-keys' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DYNAMO_DEFAULT_REGION'),
        'endpoint' => env('DYNAMODB_ENDPOINT', 'http://localstack:4566')
    ]
];
