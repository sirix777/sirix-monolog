<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\SocketHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;
use Sirix\Monolog\Exception\InvalidConfigException;

use function array_key_exists;
use function is_float;
use function is_int;

class SocketHandlerFactory implements HandlerFactoryInterface
{
    public function create(ContainerInterface $container, HandlerDefinition $handlerDefinition): SocketHandler
    {
        $configReader = ConfigReader::fromArray($handlerDefinition->options, self::class);

        return new SocketHandler(
            $configReader->requiredNonEmptyString('connection_string'),
            $configReader->enum('level', Level::class, Level::Debug),
            $configReader->bool('bubble', true),
            $configReader->bool('persistent', false),
            $this->float($handlerDefinition->options, 'timeout', 0.0),
            $this->float($handlerDefinition->options, 'writing_timeout', 10.0),
            $this->nullableFloat($handlerDefinition->options, 'connection_timeout'),
            $this->nullableInt($handlerDefinition->options, 'chunk_size'),
        );
    }

    /**
     * @param array<string, mixed> $options
     */
    private function float(array $options, string $key, float $default): float
    {
        if (! array_key_exists($key, $options)) {
            return $default;
        }

        if (is_float($options[$key]) || is_int($options[$key])) {
            return (float) $options[$key];
        }

        throw new InvalidConfigException("Socket handler option '{$key}' must be a float or int.");
    }

    /**
     * @param array<string, mixed> $options
     */
    private function nullableFloat(array $options, string $key): ?float
    {
        if (! array_key_exists($key, $options) || null === $options[$key]) {
            return null;
        }

        if (is_float($options[$key]) || is_int($options[$key])) {
            return (float) $options[$key];
        }

        throw new InvalidConfigException("Socket handler option '{$key}' must be a float, int, or null.");
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

        throw new InvalidConfigException("Socket handler option '{$key}' must be an int or null.");
    }
}
