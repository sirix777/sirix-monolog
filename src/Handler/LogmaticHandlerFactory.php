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

    public function create(ContainerInterface $container, HandlerDefinition $definition): LogmaticHandler
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        return new LogmaticHandler(
            $options->requiredNonEmptyString('token'),
            $options->string('hostname', ''),
            $options->string('app_name', ''),
            $options->bool('use_ssl', true),
            $options->enum('level', Level::class, Level::Debug),
            $options->bool('bubble', true),
            $options->bool('persistent', false),
            $this->floatOption($definition->options, 'timeout', 0.0, 'Logmatic'),
            $this->floatOption($definition->options, 'writing_timeout', 10.0, 'Logmatic'),
            $this->nullableFloatOption($definition->options, 'connection_timeout', 'Logmatic'),
            $this->nullableIntOption($definition->options, 'chunk_size', 'Logmatic'),
        );
    }
}
