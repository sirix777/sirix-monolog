<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use MongoDB\Client;
use MongoDB\Driver\Manager;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\MongoDBHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;

class MongoDBHandlerFactory implements HandlerFactoryInterface
{
    use ReflectiveHandlerFactoryTrait;

    public function create(ContainerInterface $container, HandlerDefinition $definition): HandlerInterface
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        return $this->newHandler(MongoDBHandler::class, [
            $this->serviceObject($container, $definition->options['mongodb'] ?? null, 'mongodb', 'MongoDB', [Client::class, Manager::class]),
            $options->requiredNonEmptyString('database'),
            $options->requiredNonEmptyString('collection'),
            $options->enum('level', Level::class, Level::Debug),
            $options->bool('bubble', true),
        ]);
    }
}
