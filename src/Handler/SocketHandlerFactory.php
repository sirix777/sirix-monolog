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
    public function create(ContainerInterface $container, HandlerDefinition $definition): SocketHandler
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        return new SocketHandler(
            $options->requiredNonEmptyString('connection_string'),
            $options->enum('level', Level::class, Level::Debug),
            $options->bool('bubble', true),
            $options->bool('persistent', false),
            $this->float($definition->options, 'timeout', 0.0),
            $this->float($definition->options, 'writing_timeout', 10.0),
            $this->nullableFloat($definition->options, 'connection_timeout'),
            $this->nullableInt($definition->options, 'chunk_size'),
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
