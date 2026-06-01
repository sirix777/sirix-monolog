<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\ZendMonitorHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;

class ZendMonitorHandlerFactory implements HandlerFactoryInterface
{
    public function create(ContainerInterface $container, HandlerDefinition $handlerDefinition): ZendMonitorHandler
    {
        $configReader = ConfigReader::fromArray($handlerDefinition->options, self::class);

        return new ZendMonitorHandler(
            $configReader->enum('level', Level::class, Level::Debug),
            $configReader->bool('bubble', true),
        );
    }
}
