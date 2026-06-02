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
    public function create(ContainerInterface $container, HandlerDefinition $handlerDefinition): SlackWebhookHandler
    {
        $configReader = ConfigReader::fromArray($handlerDefinition->options, self::class);

        $webhookUrl = $configReader->requiredNonEmptyString('webhook_url');
        assert('' !== $webhookUrl);

        return new SlackWebhookHandler(
            $webhookUrl,
            $configReader->optionalString('channel'),
            $configReader->optionalString('username'),
            $configReader->bool('use_attachment', true),
            $configReader->optionalString('icon_emoji'),
            $configReader->bool('use_short_attachment', false),
            $configReader->bool('include_context_and_extra', false),
            $configReader->enum('level', Level::class, Level::Critical),
            $configReader->bool('bubble', true),
            $configReader->stringList('exclude_fields', []),
        );
    }
}
