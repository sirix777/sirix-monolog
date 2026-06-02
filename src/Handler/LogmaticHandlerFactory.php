<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\LogmaticHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;

class LogmaticHandlerFactory implements HandlerFactoryInterface
{
    use HandlerOptionTrait;

    public function create(ContainerInterface $container, HandlerDefinition $handlerDefinition): LogmaticHandler
    {
        $configReader = ConfigReader::fromArray($handlerDefinition->options, self::class);

        return new LogmaticHandler(
            $configReader->requiredNonEmptyString('token'),
            $configReader->string('hostname', ''),
            $configReader->string('app_name', ''),
            $configReader->bool('use_ssl', true),
            $configReader->enum('level', Level::class, Level::Debug),
            $configReader->bool('bubble', true),
            $configReader->bool('persistent', false),
            $this->floatOption($handlerDefinition->options, 'timeout', 0.0, 'Logmatic'),
            $this->floatOption($handlerDefinition->options, 'writing_timeout', 10.0, 'Logmatic'),
            $this->nullableFloatOption($handlerDefinition->options, 'connection_timeout', 'Logmatic'),
            $this->nullableIntOption($handlerDefinition->options, 'chunk_size', 'Logmatic'),
        );
    }
}
