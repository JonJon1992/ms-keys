<?php

namespace MSKeys\Application\Keys\Actions;

use Core\Abstract\AbstractAction;
use MSKeys\Domain\Keys\Entities\Contracts\IKeyService;

class VerifyLicenseAction extends AbstractAction
{
    public function __construct(private IKeyService $service) {}
    protected function action()
    {
        return $this->respondWithJSON($this->service->verify($this->getParsedBodyDecoded()));
    }
}
