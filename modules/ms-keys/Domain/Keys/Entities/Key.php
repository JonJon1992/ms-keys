<?php

namespace MSKeys\Domain\Keys\Entities;

use DateTimeImmutable;
use Jonjon\PhpDynamo\Item\DynamoItem;
use Jonjon\PhpDynamo\Item\DynamoProperty;
use Carbon\Carbon;

class Key extends DynamoItem
{

    #[DynamoProperty(name: 'id', type: 'string', required: false)]
    protected ?string $id = null;

    #[DynamoProperty(name: 'license', type: 'string', required: true)]
    protected ?string $license = null;

    #[DynamoProperty(name: 'status', type: 'string', required: true)]
    protected ?string $status = null;

    #[DynamoProperty(name: 'expiresAt', type: 'string', required: true)]
    protected ?string $expiresAt = null;

    #[DynamoProperty(name: 'maxActivations', type: 'integer', required: true)]
    protected ?int $maxActivations = null;

    #[DynamoProperty(name: 'deviceKey', type: 'string', required: false)]
    protected ?string $deviceKey = null;

    #[DynamoProperty(name: 'createdAt', type: 'string', required: false)]
    protected ?string $createdAt = null;

    #[DynamoProperty(name: 'ttl', type: 'integer', required: false)]
    protected ?int $ttl = null;

    #[DynamoProperty(name: 'ItemType', type: 'string', required: false)]
    protected string $ItemType = 'Key';

    public static function getTableName(): string
    {
        return 'Licenses';
    }

    public function pk(): string
    {
        return "License#{$this->getLicense()}";
    }

    public function sk(): string|null
    {
        return "CreatedAt#{$this->createdAt}";
    }

    // Getters
    public function getId(): ?string
    {
        return $this->id;
    }

    public function getLicense(): ?string
    {
        return $this->license;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getExpiresAt(): ?string
    {
        return $this->expiresAt;
    }

    public function getMaxActivations(): ?int
    {
        return $this->maxActivations;
    }

    public function getDeviceKey(): ?string
    {
        return $this->deviceKey;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function getTtl(): ?int
    {
        return $this->ttl;
    }

    // Setters
    public function setId(?string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setLicense(?string $license): self
    {
        $this->license = $license;
        return $this;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function setExpiresAt(?string $expiresAt): self
    {
        $this->expiresAt = $expiresAt;
        return $this;
    }

    public function setMaxActivations(?int $maxActivations): self
    {
        $this->maxActivations = $maxActivations;
        return $this;
    }

    public function setDeviceKey(?string $deviceKey): self
    {
        $this->deviceKey = $deviceKey;
        return $this;
    }

    public function setCreatedAt(?string $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function setTtl(?int $ttl): self
    {
        $this->ttl = $ttl;
        return $this;
    }

    public static function factory($item)
    {
        $item = is_array($item) ? (object) $item : $item;

        $dynamoItem = new self();
        $dynamoItem->setId($item->id ?? null);
        $dynamoItem->setLicense($item->license ?? null);
        $dynamoItem->setStatus($item->status ?? null);
        $dynamoItem->setExpiresAt($item->expiresAt ?? null);
        $dynamoItem->setMaxActivations($item->maxActivations ?? 2);
        $dynamoItem->setDeviceKey($item->deviceKey ?? null);
        $dynamoItem->setCreatedAt($item->createdAt ?? null);
        $dynamoItem->setTtl($item->ttl ?? null);

        return $dynamoItem;
    }
}
