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
    public function create(ContainerInterface $container, HandlerDefinition $definition): CouchDBHandler
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        return new CouchDBHandler(
            $options->array('connection', []),
            $options->enum('level', Level::class, Level::Debug),
            $options->bool('bubble', true),
        );
    }
}
