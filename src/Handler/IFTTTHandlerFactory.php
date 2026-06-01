<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\IFTTTHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;

class IFTTTHandlerFactory implements HandlerFactoryInterface
{
    public function create(ContainerInterface $container, HandlerDefinition $handlerDefinition): IFTTTHandler
    {
        $configReader = ConfigReader::fromArray($handlerDefinition->options, self::class);

        return new IFTTTHandler(
            $configReader->requiredNonEmptyString('event_name'),
            $configReader->requiredNonEmptyString('secret_key'),
            $configReader->enum('level', Level::class, Level::Error),
            $configReader->bool('bubble', true),
        );
    }
}
