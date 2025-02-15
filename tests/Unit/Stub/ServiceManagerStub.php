<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Stub;

use Sirix\Monolog\ConfigInterface;
use Sirix\Monolog\Service\AbstractServiceManager;
use Monolog\Handler\HandlerInterface;

class ServiceManagerStub extends AbstractServiceManager
{
    protected ConfigInterface $configuration;

    protected bool $hasValue = true;

    final public const INTERFACE = HandlerInterface::class;

    protected function getServiceConfig(string $id): ConfigInterface
    {
        return $this->configuration;
    }

    protected function hasServiceConfig(string $id): bool
    {
        return $this->hasValue;
    }

    public function setServiceConfig(ConfigInterface $config)
    {
        $this->configuration = $config;
    }

    public function setHasServiceConfig(bool $value)
    {
        $this->hasValue = $value;
    }
}
