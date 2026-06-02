<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\LogEntriesHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;

class LogEntriesHandlerFactory implements HandlerFactoryInterface
{
    use HandlerOptionTrait;

    public function create(ContainerInterface $container, HandlerDefinition $handlerDefinition): LogEntriesHandler
    {
        $configReader = ConfigReader::fromArray($handlerDefinition->options, self::class);

        return new LogEntriesHandler(
            $configReader->requiredNonEmptyString('token'),
            $configReader->bool('use_ssl', true),
            $configReader->enum('level', Level::class, Level::Debug),
            $configReader->bool('bubble', true),
            $configReader->string('host', 'data.logentries.com'),
            $configReader->bool('persistent', false),
            $this->floatOption($handlerDefinition->options, 'timeout', 0.0, 'LogEntries'),
            $this->floatOption($handlerDefinition->options, 'writing_timeout', 10.0, 'LogEntries'),
            $this->nullableFloatOption($handlerDefinition->options, 'connection_timeout', 'LogEntries'),
            $this->nullableIntOption($handlerDefinition->options, 'chunk_size', 'LogEntries'),
        );
    }
}
