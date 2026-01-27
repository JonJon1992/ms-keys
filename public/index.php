<?php

use Core\App\App;

require __DIR__ . '/../vendor/autoload.php';

/**
 * @var  App $app
 */
$app = require __DIR__ . '/../vendor/jonjon1992/php-slim-modular/src/bootstrap.php';

$app->run();