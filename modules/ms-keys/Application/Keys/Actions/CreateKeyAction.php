<?php

namespace MSKeys\Application\Keys\Actions;

use Core\Abstract\AbstractAction;
use Core\Utils\Arr;
use MSKeys\Domain\Keys\Entities\Contracts\IKeyService;
use MSKeys\Domain\Keys\Entities\Key;

class CreateKeyAction extends AbstractAction
{
    public function __construct(private IKeyService $service) {}

    protected function action()
    {
        $resultado =  $this->service->create($this->getParsedBodyDecoded());
        $key = Arr::get($resultado->toArray(), "license");
        $id = Arr::get($resultado->toArray(), "id");
        return $this->respondWithJSON([
            "license" => $key,
            "id" => $id
        ]);
    }
}
