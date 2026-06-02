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

    public function create(ContainerInterface $container, HandlerDefinition $handlerDefinition): PushoverHandler
    {
        $configReader = ConfigReader::fromArray($handlerDefinition->options, self::class);

        $pushoverHandler = new PushoverHandler(
            $configReader->requiredNonEmptyString('token'),
            $this->stringOrStringListOption($handlerDefinition->options['users'] ?? null, 'users', 'Pushover'),
            $configReader->optionalString('title'),
            $configReader->enum('level', Level::class, Level::Critical),
            $configReader->bool('bubble', true),
            $configReader->bool('use_ssl', true),
            $configReader->enum('high_priority_level', Level::class, Level::Critical),
            $configReader->enum('emergency_level', Level::class, Level::Emergency),
            $configReader->int('retry', 30),
            $configReader->int('expire', 25200),
            $configReader->bool('persistent', false),
            $this->floatOption($handlerDefinition->options, 'timeout', 0.0, 'Pushover'),
            $this->floatOption($handlerDefinition->options, 'writing_timeout', 10.0, 'Pushover'),
            $this->nullableFloatOption($handlerDefinition->options, 'connection_timeout', 'Pushover'),
            $this->nullableIntOption($handlerDefinition->options, 'chunk_size', 'Pushover'),
        );

        $pushoverHandler->useFormattedMessage($configReader->bool('use_formatted_message', false));

        return $pushoverHandler;
    }
}
