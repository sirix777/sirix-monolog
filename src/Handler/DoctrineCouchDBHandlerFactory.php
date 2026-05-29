<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\DoctrineCouchDBHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;

class DoctrineCouchDBHandlerFactory implements HandlerFactoryInterface
{
    use ReflectiveHandlerFactoryTrait;

    public function create(ContainerInterface $container, HandlerDefinition $definition): HandlerInterface
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        return $this->newHandler(DoctrineCouchDBHandler::class, [
            $this->serviceObject($container, $definition->options['client'] ?? null, 'client', 'Doctrine CouchDB', ['Doctrine\CouchDB\CouchDBClient']),
            $options->enum('level', Level::class, Level::Debug),
            $options->bool('bubble', true),
        ]);
    }
}
