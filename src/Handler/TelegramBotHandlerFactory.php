<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\TelegramBotHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;
use Sirix\Monolog\Exception\InvalidConfigException;

use function array_key_exists;
use function is_bool;
use function is_int;

class TelegramBotHandlerFactory implements HandlerFactoryInterface
{
    public function create(ContainerInterface $container, HandlerDefinition $definition): TelegramBotHandler
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        return new TelegramBotHandler(
            $options->requiredNonEmptyString('api_key'),
            $options->requiredNonEmptyString('channel'),
            $options->enum('level', Level::class, Level::Debug),
            $options->bool('bubble', true),
            $options->optionalNonEmptyString('parse_mode'),
            $this->nullableBool($definition->options, 'disable_web_page_preview'),
            $this->nullableBool($definition->options, 'disable_notification'),
            $options->bool('split_long_messages', false),
            $options->bool('delay_between_messages', false),
            $this->nullableInt($definition->options, 'topic'),
        );
    }

    /**
     * @param array<string, mixed> $options
     */
    private function nullableBool(array $options, string $key): ?bool
    {
        if (! array_key_exists($key, $options) || null === $options[$key]) {
            return null;
        }

        if (is_bool($options[$key])) {
            return $options[$key];
        }

        throw new InvalidConfigException("Telegram bot handler option '{$key}' must be a bool or null.");
    }

    /**
     * @param array<string, mixed> $options
     */
    private function nullableInt(array $options, string $key): ?int
    {
        if (! array_key_exists($key, $options) || null === $options[$key]) {
            return null;
        }

        if (is_int($options[$key])) {
            return $options[$key];
        }

        throw new InvalidConfigException("Telegram bot handler option '{$key}' must be an int or null.");
    }
}
