<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use MongoDB\Client;
use MongoDB\Driver\Manager;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\MongoDBHandler;
use Monolog\Level;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use ReflectionException;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;

class MongoDBHandlerFactory implements HandlerFactoryInterface
{
    use ReflectiveHandlerFactoryTrait;

    /**
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    public function create(ContainerInterface $container, HandlerDefinition $handlerDefinition): HandlerInterface
    {
        $configReader = ConfigReader::fromArray($handlerDefinition->options, self::class);

        return $this->newHandler(MongoDBHandler::class, [
            $this->serviceObject($container, $handlerDefinition->options['mongodb'] ?? null, 'mongodb', 'MongoDB', [Client::class, Manager::class]),
            $configReader->requiredNonEmptyString('database'),
            $configReader->requiredNonEmptyString('collection'),
            $configReader->enum('level', Level::class, Level::Debug),
            $configReader->bool('bubble', true),
        ]);
    }
}
