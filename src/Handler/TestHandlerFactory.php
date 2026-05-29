<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\TestHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;

class TestHandlerFactory implements HandlerFactoryInterface
{
    public function create(ContainerInterface $container, HandlerDefinition $definition): TestHandler
    {
        $options = ConfigReader::fromArray($definition->options, self::class);
        $level = $options->enum('level', Level::class, Level::Debug);

        return new TestHandler(
            $level,
            $options->bool('bubble', true),
        );
    }
}
