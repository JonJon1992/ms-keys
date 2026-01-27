<?php

namespace MSKeys;

use MSKeys\Domain\Keys\Entities\Contracts\IKeyRepository;
use MSKeys\Domain\Keys\Entities\Contracts\IKeyService;
use MSKeys\Infrastructure\Repositories\KeyRepository;
use MSKeys\Domain\Keys\Services\KeyService;

class Module extends Router
{
    protected string $namespace = 'ms-keys';


    protected array $bindings = [
        IKeyRepository::class => KeyRepository::class,
        IKeyService::class => KeyService::class,
    ];
}
