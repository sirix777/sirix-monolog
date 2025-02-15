<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\MissingExtensionException;
use Monolog\Handler\TelegramBotHandler;
use Monolog\Level;
use Sirix\Monolog\FactoryInterface;

class TelegramBotHandlerFactory implements FactoryInterface
{
    /**
     * @throws MissingExtensionException
     */
    public function __invoke(array $options): TelegramBotHandler
    {
        $apiKey = (string) ($options['apiKey'] ?? '');
        $channel = (string) ($options['channel'] ?? '');
        $level = $options['level'] ?? Level::Debug;
        $bubble = (bool) ($options['bubble'] ?? true);

        return new TelegramBotHandler(
            $apiKey,
            $channel,
            $level,
            $bubble
        );
    }
}
