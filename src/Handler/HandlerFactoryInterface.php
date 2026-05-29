<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\HandlerInterface;
use Psr\Container\ContainerInterface;
use Sirix\Monolog\Config\HandlerDefinition;

interface HandlerFactoryInterface
{
    public function create(ContainerInterface $container, HandlerDefinition $definition): HandlerInterface;
}
