<?php

namespace MSKeys\Infrastructure\Repositories;

use Core\Exceptions\Exception\InfraException;
use MSKeys\Domain\Keys\Entities\Key;
use MSKeys\Domain\Keys\Entities\Contracts\IKeyRepository;
use MSKeys\Module;

class KeyRepository implements IKeyRepository
{
    private Module $module;

    public function __construct(Module $module)
    {
        $this->module = $module;
    }

    public function create($key): void
    {
        dynamo($this->module->namespace())->save($key);
    }

    public function fetch(string $id): ?Key
    {
        $table = Key::factory([])
            ->setLicense($id);

        $resultado = dynamo($this->module->namespace())->query(
            tableName: $table->getTableName(),
            keyConditionExpression: 'PK = :pk',
            values: [':pk' => $table->pk()],
            class: Key::class
        );

        if (empty($resultado['items'])) {
            throw new InfraException("License key not found");
        }

        return Key::factory($resultado['items'][0]);
    }

    public function update(Key $key): void
    {
        dynamo($this->module->namespace())->save($key);
    }

    public function updateDeviceKey(Key $key): void
    {
        dynamo($this->module->namespace())->update(
            tableName: $key->getTableName(),
            key: $key->getKey(),
            updateExpression: 'set deviceKey = :deviceKey',
            values: [':deviceKey' => $key->getDeviceKey()]
        );
    }

    public function delete(string $key): void {}
}
