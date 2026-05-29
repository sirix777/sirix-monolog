<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\PushoverHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;

class PushoverHandlerFactory implements HandlerFactoryInterface
{
    use HandlerOptionTrait;

    public function create(ContainerInterface $container, HandlerDefinition $definition): PushoverHandler
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        $handler = new PushoverHandler(
            $options->requiredNonEmptyString('token'),
            $this->stringOrStringListOption($definition->options['users'] ?? null, 'users', 'Pushover'),
            $options->optionalString('title'),
            $options->enum('level', Level::class, Level::Critical),
            $options->bool('bubble', true),
            $options->bool('use_ssl', true),
            $options->enum('high_priority_level', Level::class, Level::Critical),
            $options->enum('emergency_level', Level::class, Level::Emergency),
            $options->int('retry', 30),
            $options->int('expire', 25200),
            $options->bool('persistent', false),
            $this->floatOption($definition->options, 'timeout', 0.0, 'Pushover'),
            $this->floatOption($definition->options, 'writing_timeout', 10.0, 'Pushover'),
            $this->nullableFloatOption($definition->options, 'connection_timeout', 'Pushover'),
            $this->nullableIntOption($definition->options, 'chunk_size', 'Pushover'),
        );

        $handler->useFormattedMessage($options->bool('use_formatted_message', false));

        return $handler;
    }
}
