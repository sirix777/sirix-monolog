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

    public function create(ContainerInterface $container, HandlerDefinition $definition): SlackHandler
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        return new SlackHandler(
            $options->requiredNonEmptyString('token'),
            $options->requiredNonEmptyString('channel'),
            $options->optionalString('username'),
            $options->bool('use_attachment', true),
            $options->optionalString('icon_emoji'),
            $options->enum('level', Level::class, Level::Critical),
            $options->bool('bubble', true),
            $options->bool('use_short_attachment', false),
            $options->bool('include_context_and_extra', false),
            $options->stringList('exclude_fields', []),
            $options->bool('persistent', false),
            $this->floatOption($definition->options, 'timeout', 0.0, 'Slack'),
            $this->floatOption($definition->options, 'writing_timeout', 10.0, 'Slack'),
            $this->nullableFloatOption($definition->options, 'connection_timeout', 'Slack'),
            $this->nullableIntOption($definition->options, 'chunk_size', 'Slack'),
        );
    }
}
