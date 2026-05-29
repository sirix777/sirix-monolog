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
    public function create(ContainerInterface $container, HandlerDefinition $definition): FirePHPHandler
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        return new FirePHPHandler(
            $options->enum('level', Level::class, Level::Debug),
            $options->bool('bubble', true),
        );
    }
}
