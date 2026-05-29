<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\LogglyHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;

class LogglyHandlerFactory implements HandlerFactoryInterface
{
    use HandlerOptionTrait;

    public function create(ContainerInterface $container, HandlerDefinition $definition): LogglyHandler
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        $handler = new LogglyHandler(
            $options->requiredNonEmptyString('token'),
            $options->enum('level', Level::class, Level::Debug),
            $options->bool('bubble', true),
        );

        if ($options->has('tag')) {
            $handler->setTag($this->stringOrStringListOption($definition->options['tag'], 'tag', 'Loggly'));
        }

        return $handler;
    }
}
