<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\FirePHPHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;

class FirePHPHandlerFactory implements HandlerFactoryInterface
{
    public function create(ContainerInterface $container, HandlerDefinition $handlerDefinition): FirePHPHandler
    {
        $configReader = ConfigReader::fromArray($handlerDefinition->options, self::class);

        return new FirePHPHandler(
            $configReader->enum('level', Level::class, Level::Debug),
            $configReader->bool('bubble', true),
        );
    }
}
