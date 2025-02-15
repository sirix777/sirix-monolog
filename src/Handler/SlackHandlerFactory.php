<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\MissingExtensionException;
use Monolog\Handler\SlackHandler;
use Monolog\Level;
use Sirix\Monolog\FactoryInterface;

class SlackHandlerFactory implements FactoryInterface
{
    /**
     * @throws MissingExtensionException
     */
    public function __invoke(array $options): SlackHandler
    {
        $token = (string) ($options['token'] ?? '');
        $channel = (string) ($options['channel'] ?? '');
        $userName = $options['userName'] ?? null;
        $useAttachment = (bool) ($options['useAttachment'] ?? true);
        $iconEmoji = $options['iconEmoji'] ?? null;
        $level = $options['level'] ?? Level::Debug;
        $bubble = (bool) ($options['bubble'] ?? true);
        $useShortAttachment = (bool) ($options['useShortAttachment'] ?? false);
        $includeContext = (bool) ($options['includeContextAndExtra'] ?? false);
        $excludeFields = (array) ($options['excludeFields'] ?? []);

        return new SlackHandler(
            $token,
            $channel,
            $userName,
            $useAttachment,
            $iconEmoji,
            $level,
            $bubble,
            $useShortAttachment,
            $includeContext,
            $excludeFields
        );
    }
}
