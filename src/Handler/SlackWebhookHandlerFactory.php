<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\MissingExtensionException;
use Monolog\Handler\SlackWebhookHandler;
use Monolog\Level;
use Sirix\Monolog\FactoryInterface;

class SlackWebhookHandlerFactory implements FactoryInterface
{
    /**
     * @throws MissingExtensionException
     */
    public function __invoke(array $options): SlackWebhookHandler
    {
        $webhookUrl = (string) ($options['webhookUrl'] ?? '');
        $channel = $options['channel'] ?? null;
        $userName = $options['userName'] ?? null;
        $useAttachment = (bool) ($options['useAttachment'] ?? true);
        $iconEmoji = $options['iconEmoji'] ?? null;
        $useShortAttachment = (bool) ($options['useShortAttachment'] ?? false);
        $includeContext = (bool) ($options['includeContextAndExtra'] ?? false);
        $level = $options['level'] ?? Level::Debug;
        $bubble = (bool) ($options['bubble'] ?? true);
        $excludeFields = (array) ($options['excludeFields'] ?? []);

        return new SlackWebhookHandler(
            $webhookUrl,
            $channel,
            $userName,
            $useAttachment,
            $iconEmoji,
            $useShortAttachment,
            $includeContext,
            $level,
            $bubble,
            $excludeFields
        );
    }
}
