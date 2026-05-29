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

    public function create(ContainerInterface $container, HandlerDefinition $definition): LogEntriesHandler
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        return new LogEntriesHandler(
            $options->requiredNonEmptyString('token'),
            $options->bool('use_ssl', true),
            $options->enum('level', Level::class, Level::Debug),
            $options->bool('bubble', true),
            $options->string('host', 'data.logentries.com'),
            $options->bool('persistent', false),
            $this->floatOption($definition->options, 'timeout', 0.0, 'LogEntries'),
            $this->floatOption($definition->options, 'writing_timeout', 10.0, 'LogEntries'),
            $this->nullableFloatOption($definition->options, 'connection_timeout', 'LogEntries'),
            $this->nullableIntOption($definition->options, 'chunk_size', 'LogEntries'),
        );
    }
}
