<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Stub;

use Monolog\Handler\HandlerInterface;
use Psr\Container\ContainerInterface;
use Sirix\Monolog\ContainerAwareInterface;
use Sirix\Monolog\FactoryInterface;

class FactoryStub implements FactoryInterface, ContainerAwareInterface
{
    protected ContainerInterface $container;

    public function __invoke(array $options): HandlerInterface
    {
        return new HandlerStub();
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }
}
