<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\ChromePHPHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;

class ChromePHPHandlerFactory implements HandlerFactoryInterface
{
    public function create(ContainerInterface $container, HandlerDefinition $definition): ChromePHPHandler
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        return new ChromePHPHandler(
            $options->enum('level', Level::class, Level::Debug),
            $options->bool('bubble', true),
        );
    }
}
