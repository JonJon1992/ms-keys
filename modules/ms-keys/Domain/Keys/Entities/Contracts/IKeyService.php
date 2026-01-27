<?php

namespace MSKeys\Domain\Keys\Entities\Contracts;

interface IKeyService
{
    public function create($params);
    public function verify($params);
}
