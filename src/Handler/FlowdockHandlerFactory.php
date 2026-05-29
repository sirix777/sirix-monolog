<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\FlowdockHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;

class FlowdockHandlerFactory implements HandlerFactoryInterface
{
    use HandlerOptionTrait;

    public function create(ContainerInterface $container, HandlerDefinition $definition): FlowdockHandler
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        return new FlowdockHandler(
            $options->requiredNonEmptyString('api_token'),
            $options->enum('level', Level::class, Level::Debug),
            $options->bool('bubble', true),
            $options->bool('persistent', false),
            $this->floatOption($definition->options, 'timeout', 0.0, 'Flowdock'),
            $this->floatOption($definition->options, 'writing_timeout', 10.0, 'Flowdock'),
            $this->nullableFloatOption($definition->options, 'connection_timeout', 'Flowdock'),
            $this->nullableIntOption($definition->options, 'chunk_size', 'Flowdock'),
        );
    }
}
