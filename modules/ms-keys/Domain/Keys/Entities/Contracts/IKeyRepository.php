<?php

namespace MSKeys\Domain\Keys\Entities\Contracts;

use MSKeys\Domain\Keys\Entities\Key;

interface IKeyRepository
{
    public function create(Key $key): void;

    public function fetch(string $key): ?Key;

    public function update(Key $key): void;

    public function updateDeviceKey(Key $key): void;

    public function delete(string $key): void;
}
