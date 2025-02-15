<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Stub;

use Monolog\Handler\HandlerInterface;
use Sirix\Monolog\ConfigInterface;
use Sirix\Monolog\Service\AbstractServiceManager;

class ServiceManagerStub extends AbstractServiceManager
{
    final public const INTERFACE = HandlerInterface::class;
    protected ConfigInterface $configuration;

    protected bool $hasValue = true;

    public function setServiceConfig(ConfigInterface $config): void
    {
        $this->configuration = $config;
    }

    public function setHasServiceConfig(bool $value): void
    {
        $this->hasValue = $value;
    }

    protected function getServiceConfig(string $id): ConfigInterface
    {
        return $this->configuration;
    }

    protected function hasServiceConfig(string $id): bool
    {
        return $this->hasValue;
    }
}
