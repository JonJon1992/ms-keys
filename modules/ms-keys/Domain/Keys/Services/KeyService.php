<?php

namespace MSKeys\Domain\Keys\Services;

use Carbon\Carbon;
use Core\Exceptions\Exception\DomainException;
use Core\Utils\Time;
use MSKeys\Domain\Keys\Entities\Contracts\IKeyRepository;
use MSKeys\Domain\Keys\Entities\Contracts\IKeyService;
use MSKeys\Domain\Keys\Entities\Key;
use MSKeys\Module;
use MSKeys\Application\Jobs\SendEmail\SendEmailJob;
use Ramsey\Uuid\Uuid;
use Throwable;

class KeyService implements IKeyService
{
    public function __construct(private IKeyRepository $repository, private Module $module) {}

    public function create($params)
    {
        try {
            $this->module->queue()->push(new SendEmailJob(['message' => 'teste']));
            $key = $this->saveToDynamo($params['days']);
            return $key;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function verify($params)
    {
        try {
            $cached = $this->getRedis()->get('License#' . $params['license']);
            if ($cached) {
                $item = Key::factory(json_decode($cached, true));
            } else {
                $item = $this->repository->fetch($params['license']);
                if (empty($item->getDeviceKey())) {
                    $item->setDeviceKey($params['device']);
                    $this->repository->update($item);
                }
                $this->getRedis()->set('License#' . $item->getLicense(), $item->toJson(), Time::_7D);
            }

            $device = $params['device'];
            if ($device != $item->getDeviceKey()) {
                throw new DomainException("Device not authorized");
            }

            $now = Carbon::now();
            $expiresAt = Carbon::parse($item->getExpiresAt());

            if ($now->greaterThan($expiresAt)) {
                throw new DomainException("License has expired");
            }

            return [
                'license' => $item->getLicense(),
                'expiresAt' => $item->getExpiresAt(),
                'status' => $item->getStatus(),
                'type' => 'demo'
            ];
        } catch (Throwable $e) {
            throw new DomainException($e->getMessage(), 409);
        }
    }

    private function generate(): string
    {
        $randomBytes = random_bytes(16);
        $hash = rtrim(strtr(base64_encode($randomBytes), '+/', '-_'), '=');
        return $hash;
    }

    private function saveToDynamo($days)
    {
        $key = new Key();
        $now = Carbon::now();
        $expire = $now->copy()->addDays($days);
        $key->setId(Uuid::uuid7()->toString());
        $key->setLicense($this->generate());
        $key->setCreatedAt($now);
        $key->setExpiresAt($expire);
        $key->setTtl($expire->timestamp);
        $key->setStatus("active");
        $key->setMaxActivations(1);
        $this->repository->create($key);

        return $key;
    }

    private function getRedis()
    {
        return redis($this->module->namespace());
    }
}
