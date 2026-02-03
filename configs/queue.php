<?php

$sqsConnection = [
    'driver' => 'sqs',
    'key' => env('AWS_ACCESS_KEY_ID'),
    'secret' => env('AWS_SECRET_ACCESS_KEY'),
    'prefix' => env('SQS_PREFIX', ''),
    'queue' => env('SQS_QUEUE', 'default'),
    'suffix' => env('SQS_SUFFIX', ''),
    'region' => env('AWS_REGION', 'sa-east-1'),
    'after_commit' => false,
];

return [
    'default' => 'ms-keys',
    'ms-keys' => [
        'default_connection' => env('QUEUE_CONNECTION', 'sqs'),
        'connections' => [
            'sync' => [
                'driver' => 'sync',
            ],
            'database' => [
                'driver' => 'database',
                'table' => 'jobs',
                'queue' => 'default',
                'retry_after' => 90,
            ],
            'redis' => [
                'driver' => 'redis',
                'connection' => 'default',
                'queue' => 'default',
                'retry_after' => 90,
                'block_for' => null,
            ],
            'sqs' => $sqsConnection,
            // Laravel Queue usa a conexão "default"; sem ela ocorre "The [default] queue connection has not been configured."
            'default' => $sqsConnection,
        ],

        'failed' => [
            'table' => 'failed_jobs',
        ],
    ],
];
