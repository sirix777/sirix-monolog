<?php

declare(strict_types=1);

namespace Sirix\Monolog\Processor;

use ArrayAccess;
use Monolog\Processor\WebProcessor;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ContainerResolver;
use Sirix\Monolog\Config\ProcessorDefinition;
use Sirix\Monolog\Exception\InvalidConfigException;

use function array_key_exists;
use function is_array;
use function is_string;

class WebProcessorFactory implements ProcessorFactoryInterface
{
    public function create(ContainerInterface $container, ProcessorDefinition $definition): WebProcessor
    {
        return new WebProcessor(
            $this->serverData($container, $definition->options['server_data'] ?? null),
            $this->optionalArray($definition->options, 'extra_fields'),
        );
    }

    private function serverData(ContainerInterface $container, mixed $serverData): mixed
    {
        if (null === $serverData) {
            return null;
        }

        if (is_array($serverData) || $serverData instanceof ArrayAccess) {
            return $serverData;
        }

        if (is_string($serverData)) {
            return ContainerResolver::forContext($container, self::class)->getExisting($serverData);
        }

        throw new InvalidConfigException('Web processor option "server_data" must be an array, ArrayAccess, service id, or null.');
    }

    /**
     * @param array<string, mixed> $options
     */
    private function optionalArray(array $options, string $key): ?array
    {
        if (! array_key_exists($key, $options) || null === $options[$key]) {
            return null;
        }

        if (! is_array($options[$key])) {
            throw new InvalidConfigException("Web processor option '{$key}' must be an array or null.");
        }

        return $options[$key];
    }
}
