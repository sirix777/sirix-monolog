<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\NewRelicHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;

class NewRelicHandlerFactory implements HandlerFactoryInterface
{
    public function create(ContainerInterface $container, HandlerDefinition $handlerDefinition): NewRelicHandler
    {
        $configReader = ConfigReader::fromArray($handlerDefinition->options, self::class);

        return new NewRelicHandler(
            $configReader->enum('level', Level::class, Level::Error),
            $configReader->bool('bubble', true),
            $configReader->optionalString('app_name'),
            $configReader->bool('explode_arrays', false),
            $configReader->optionalString('transaction_name'),
        );
    }
}
