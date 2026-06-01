<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\ContainerResolver\ContainerResolver;
use Sirix\Monolog\Config\HandlerDefinition;
use Sirix\Monolog\Exception\InvalidConfigException;

use function array_key_exists;
use function is_int;
use function is_string;

class StreamHandlerFactory implements HandlerFactoryInterface
{
    public function create(ContainerInterface $container, HandlerDefinition $definition): StreamHandler
    {
        $options = ConfigReader::fromArray($definition->options, self::class);
        $stream = $options->required('stream');

        if (is_string($stream) && $container->has($stream)) {
            $stream = ContainerResolver::forContext($container, self::class)->getExisting($stream);
        }

        $level = $options->enum('level', Level::class, Level::Debug);

        return new StreamHandler(
            $stream,
            $level,
            $options->bool('bubble', true),
            $this->nullableInt($definition->options, 'file_permission'),
            $options->bool('use_locking', false),
        );
    }

    /**
     * @param array<string, mixed> $options
     */
    private function nullableInt(array $options, string $key): ?int
    {
        if (! array_key_exists($key, $options) || null === $options[$key]) {
            return null;
        }

        if (! is_int($options[$key])) {
            throw new InvalidConfigException("Stream handler option '{$key}' must be an int or null.");
        }

        return $options[$key];
    }
}
