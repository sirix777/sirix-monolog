<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\NoopHandler;
use Psr\Container\ContainerInterface;
use Sirix\Monolog\Config\HandlerDefinition;

class NoopHandlerFactory implements HandlerFactoryInterface
{
    public function create(ContainerInterface $container, HandlerDefinition $definition): NoopHandler
    {
        return new NoopHandler();
    }
}
