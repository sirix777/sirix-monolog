<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\BrowserConsoleHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;

class BrowserConsoleHandlerFactory implements HandlerFactoryInterface
{
    public function create(ContainerInterface $container, HandlerDefinition $definition): BrowserConsoleHandler
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        return new BrowserConsoleHandler(
            $options->enum('level', Level::class, Level::Debug),
            $options->bool('bubble', true),
        );
    }
}
