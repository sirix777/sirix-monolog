<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Support;

use Monolog\Handler\HandlerInterface;
use Monolog\Handler\NoopHandler;
use Psr\Container\ContainerInterface;
use Sirix\Monolog\Config\HandlerDefinition;
use Sirix\Monolog\Handler\HandlerFactoryInterface;

final class CustomNoopHandlerFactory implements HandlerFactoryInterface
{
    public function create(ContainerInterface $container, HandlerDefinition $definition): HandlerInterface
    {
        return new NoopHandler();
    }
}
