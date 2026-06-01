<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\CubeHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;

class CubeHandlerFactory implements HandlerFactoryInterface
{
    public function create(ContainerInterface $container, HandlerDefinition $handlerDefinition): CubeHandler
    {
        $configReader = ConfigReader::fromArray($handlerDefinition->options, self::class);

        return new CubeHandler(
            $configReader->requiredNonEmptyString('url'),
            $configReader->enum('level', Level::class, Level::Debug),
            $configReader->bool('bubble', true),
        );
    }
}
