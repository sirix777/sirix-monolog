<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\HandlerInterface;
use Monolog\Handler\RollbarHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;

class RollbarHandlerFactory implements HandlerFactoryInterface
{
    use ReflectiveHandlerFactoryTrait;

    public function create(ContainerInterface $container, HandlerDefinition $definition): HandlerInterface
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        return $this->newHandler(RollbarHandler::class, [
            $this->serviceObject($container, $definition->options['rollbar_logger'] ?? null, 'rollbar_logger', 'Rollbar', ['Rollbar\RollbarLogger']),
            $options->enum('level', Level::class, Level::Error),
            $options->bool('bubble', true),
        ]);
    }
}
