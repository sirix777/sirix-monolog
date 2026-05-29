<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\SlackWebhookHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;

use function assert;

class SlackWebhookHandlerFactory implements HandlerFactoryInterface
{
    public function create(ContainerInterface $container, HandlerDefinition $definition): SlackWebhookHandler
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        $webhookUrl = $options->requiredNonEmptyString('webhook_url');
        assert('' !== $webhookUrl);

        return new SlackWebhookHandler(
            $webhookUrl,
            $options->optionalString('channel'),
            $options->optionalString('username'),
            $options->bool('use_attachment', true),
            $options->optionalString('icon_emoji'),
            $options->bool('use_short_attachment', false),
            $options->bool('include_context_and_extra', false),
            $options->enum('level', Level::class, Level::Critical),
            $options->bool('bubble', true),
            $options->stringList('exclude_fields', []),
        );
    }
}
