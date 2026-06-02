<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\SlackHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;

class SlackHandlerFactory implements HandlerFactoryInterface
{
    use HandlerOptionTrait;

    public function create(ContainerInterface $container, HandlerDefinition $handlerDefinition): SlackHandler
    {
        $configReader = ConfigReader::fromArray($handlerDefinition->options, self::class);

        return new SlackHandler(
            $configReader->requiredNonEmptyString('token'),
            $configReader->requiredNonEmptyString('channel'),
            $configReader->optionalString('username'),
            $configReader->bool('use_attachment', true),
            $configReader->optionalString('icon_emoji'),
            $configReader->enum('level', Level::class, Level::Critical),
            $configReader->bool('bubble', true),
            $configReader->bool('use_short_attachment', false),
            $configReader->bool('include_context_and_extra', false),
            $configReader->stringList('exclude_fields', []),
            $configReader->bool('persistent', false),
            $this->floatOption($handlerDefinition->options, 'timeout', 0.0, 'Slack'),
            $this->floatOption($handlerDefinition->options, 'writing_timeout', 10.0, 'Slack'),
            $this->nullableFloatOption($handlerDefinition->options, 'connection_timeout', 'Slack'),
            $this->nullableIntOption($handlerDefinition->options, 'chunk_size', 'Slack'),
        );
    }
}
