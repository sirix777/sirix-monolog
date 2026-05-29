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
    public function create(ContainerInterface $container, HandlerDefinition $definition): IFTTTHandler
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        return new IFTTTHandler(
            $options->requiredNonEmptyString('event_name'),
            $options->requiredNonEmptyString('secret_key'),
            $options->enum('level', Level::class, Level::Error),
            $options->bool('bubble', true),
        );
    }
}
