<?php

namespace MSKeys;

use Core\App\Module;
use Core\Middleware\ClientMiddleware;
use MSKeys\Application\Keys\Actions\VerifyLicenseAction;
use Slim\Interfaces\RouteCollectorProxyInterface as R;
use MSKeys\Application\Keys\Actions\CreateKeyAction;

class Router extends Module
{
    public function v1(R $v1)
    {
        return $v1->group('/keys', function (R $group) {
            $group->post('', CreateKeyAction::class);
            $group->post('/verify', VerifyLicenseAction::class);
        })->add(ClientMiddleware::class);
    }
}
