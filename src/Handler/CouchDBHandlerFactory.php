<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\CouchDBHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;

class CouchDBHandlerFactory implements HandlerFactoryInterface
{
    public function create(ContainerInterface $container, HandlerDefinition $handlerDefinition): CouchDBHandler
    {
        $configReader = ConfigReader::fromArray($handlerDefinition->options, self::class);

        return new CouchDBHandler(
            $configReader->array('connection', []),
            $configReader->enum('level', Level::class, Level::Debug),
            $configReader->bool('bubble', true),
        );
    }
}
