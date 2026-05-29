<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\NullHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;

class NullHandlerFactory implements HandlerFactoryInterface
{
    public function create(ContainerInterface $container, HandlerDefinition $definition): NullHandler
    {
        $options = ConfigReader::fromArray($definition->options, self::class);
        $level = $options->enum('level', Level::class, Level::Debug);

        return new NullHandler($level);
    }
}
