<?php

declare(strict_types=1);

namespace Sirix\Monolog\Config;

use Sirix\Monolog\Exception\InvalidFactoryException;
use Sirix\Monolog\Exception\MissingConfigException;
use Sirix\Monolog\Exception\UnknownChannelException;

final readonly class MonologConfig
{
    /**
     * @param array<non-empty-string, non-empty-string>    $loggerServices
     * @param array<non-empty-string, ChannelDefinition>   $channels
     * @param array<non-empty-string, HandlerDefinition>   $handlers
     * @param array<non-empty-string, FormatterDefinition> $formatters
     * @param array<non-empty-string, ProcessorDefinition> $processors
     */
    public function __construct(
        public array $loggerServices,
        public array $channels,
        public array $handlers,
        public array $formatters,
        public array $processors,
        public FactoryMap $factoryMap,
    ) {}

    public function channel(string $id): ChannelDefinition
    {
        return $this->channels[$id] ?? throw UnknownChannelException::forChannel($id);
    }

    public function channelForLoggerService(string $serviceId): string
    {
        return $this->loggerServices[$serviceId] ?? throw UnknownChannelException::forLoggerService($serviceId);
    }

    public function handler(string $id): HandlerDefinition
    {
        return $this->handlers[$id] ?? throw new MissingConfigException("Unable to locate monolog handler '{$id}'.");
    }

    public function formatter(string $id): FormatterDefinition
    {
        return $this->formatters[$id] ?? throw new MissingConfigException("Unable to locate monolog formatter '{$id}'.");
    }

    public function processor(string $id): ProcessorDefinition
    {
        return $this->processors[$id] ?? throw new MissingConfigException("Unable to locate monolog processor '{$id}'.");
    }

    public function handlerFactory(string $type): string
    {
        return $this->factoryMap->handlerFactories[$type] ?? throw InvalidFactoryException::forMissingFactory($type);
    }

    public function formatterFactory(string $type): string
    {
        return $this->factoryMap->formatterFactories[$type] ?? throw InvalidFactoryException::forMissingFactory($type);
    }

    public function processorFactory(string $type): string
    {
        return $this->factoryMap->processorFactories[$type] ?? throw InvalidFactoryException::forMissingFactory($type);
    }
}
