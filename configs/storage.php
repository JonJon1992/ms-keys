<?php

$driver = env('S3_DRIVER', 's3');
$region = env('S3_REGION', 'sa-east-1');
$access_key = env('S3_ACCESS_KEY', 'test');
$secret_key = env('S3_SECRET_KEY','test');
$endpoint = env('S3_ENDPOINT', '');
return [
    'default' => 'ms-auth',
    'ms-auth' => [
        'driver' => $driver,
        'region' => $region,
        'access_key' => $access_key,
        'secret_key' => $secret_key,
        'bucket' => env('S3_BUCKET')
    ]
];