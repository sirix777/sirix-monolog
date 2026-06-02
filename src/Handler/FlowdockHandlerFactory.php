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

    public function create(ContainerInterface $container, HandlerDefinition $handlerDefinition): FlowdockHandler
    {
        $configReader = ConfigReader::fromArray($handlerDefinition->options, self::class);

        return new FlowdockHandler(
            $configReader->requiredNonEmptyString('api_token'),
            $configReader->enum('level', Level::class, Level::Debug),
            $configReader->bool('bubble', true),
            $configReader->bool('persistent', false),
            $this->floatOption($handlerDefinition->options, 'timeout', 0.0, 'Flowdock'),
            $this->floatOption($handlerDefinition->options, 'writing_timeout', 10.0, 'Flowdock'),
            $this->nullableFloatOption($handlerDefinition->options, 'connection_timeout', 'Flowdock'),
            $this->nullableIntOption($handlerDefinition->options, 'chunk_size', 'Flowdock'),
        );
    }
}
