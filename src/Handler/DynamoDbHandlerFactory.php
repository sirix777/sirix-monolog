<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\DynamoDbHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Level;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use ReflectionException;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;

class DynamoDbHandlerFactory implements HandlerFactoryInterface
{
    use ReflectiveHandlerFactoryTrait;

    /**
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     */
    public function create(ContainerInterface $container, HandlerDefinition $handlerDefinition): HandlerInterface
    {
        $configReader = ConfigReader::fromArray($handlerDefinition->options, self::class);

        return $this->newHandler(DynamoDbHandler::class, [
            $this->serviceObject($container, $handlerDefinition->options['client'] ?? null, 'client', 'DynamoDB', ['Aws\DynamoDb\DynamoDbClient']),
            $configReader->requiredNonEmptyString('table'),
            $configReader->enum('level', Level::class, Level::Debug),
            $configReader->bool('bubble', true),
        ]);
    }
}
