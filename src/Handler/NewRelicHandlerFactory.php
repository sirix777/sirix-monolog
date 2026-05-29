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
    public function create(ContainerInterface $container, HandlerDefinition $definition): NewRelicHandler
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        return new NewRelicHandler(
            $options->enum('level', Level::class, Level::Error),
            $options->bool('bubble', true),
            $options->optionalString('app_name'),
            $options->bool('explode_arrays', false),
            $options->optionalString('transaction_name'),
        );
    }
}
