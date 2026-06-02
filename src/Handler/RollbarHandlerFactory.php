<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\HandlerInterface;
use Monolog\Handler\RollbarHandler;
use Monolog\Level;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use ReflectionException;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;

class RollbarHandlerFactory implements HandlerFactoryInterface
{
    use ReflectiveHandlerFactoryTrait;

    /**
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     */
    public function create(ContainerInterface $container, HandlerDefinition $handlerDefinition): HandlerInterface
    {
        $configReader = ConfigReader::fromArray($handlerDefinition->options, self::class);

        return $this->newHandler(RollbarHandler::class, [
            $this->serviceObject($container, $handlerDefinition->options['rollbar_logger'] ?? null, 'rollbar_logger', 'Rollbar', ['Rollbar\RollbarLogger']),
            $configReader->enum('level', Level::class, Level::Error),
            $configReader->bool('bubble', true),
        ]);
    }
}
