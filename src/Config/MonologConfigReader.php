<?php

declare(strict_types=1);

namespace Sirix\Monolog\Config;

use BackedEnum;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Enum\ConfigKey;
use Sirix\Monolog\Enum\HandlerType;
use Sirix\Monolog\Exception\InvalidConfigException;
use Sirix\Monolog\Exception\MissingConfigException;
use Sirix\Monolog\Formatter\BuiltInFormatterFactories;
use Sirix\Monolog\Formatter\FormatterFactoryInterface;
use Sirix\Monolog\Handler\BuiltInHandlerFactories;
use Sirix\Monolog\Handler\HandlerFactoryInterface;
use Sirix\Monolog\Processor\BuiltInProcessorFactories;
use Sirix\Monolog\Processor\ProcessorFactoryInterface;

use function class_exists;
use function is_a;
use function is_array;
use function is_string;
use function trim;

final class MonologConfigReader
{
    /**
     * @param array<string, mixed> $config
     */
    public function read(array $config): MonologConfig
    {
        $configReader = ConfigReader::fromArray($config, self::class);
        $root = $configReader->map(ConfigKey::Root->value, []);
        $rootReader = ConfigReader::fromArray($root, self::class);

        $handlers = $this->readHandlers($rootReader);
        $channels = $this->readChannels($rootReader);
        $formatters = $this->readFormatters($rootReader);
        $processors = $this->readProcessors($rootReader);
        $loggerServices = $this->readLoggerServices($rootReader);
        $factoryMap = $this->readFactoryMap($rootReader);

        $this->assertLoggerServiceReferences($loggerServices, $channels);
        $this->assertChannelReferences($channels, $handlers, $processors);
        $this->assertHandlerReferences($handlers, $formatters, $processors);

        return new MonologConfig(
            loggerServices: $loggerServices,
            channels: $channels,
            handlers: $handlers,
            formatters: $formatters,
            processors: $processors,
            factoryMap: $factoryMap,
        );
    }

    /**
     * @return array<non-empty-string, LoggerServiceDefinition>
     */
    private function readLoggerServices(ConfigReader $configReader): array
    {
        $services = $configReader->map(ConfigKey::LoggerServices->value, [
            Logger::class => 'default',
            LoggerInterface::class => 'default',
            'logger' => 'default',
        ]);

        /** @var array<non-empty-string, LoggerServiceDefinition> $result */
        $result = [];

        foreach ($services as $serviceId => $serviceConfig) {
            $serviceId = $this->nonEmptyString($serviceId, ConfigKey::LoggerServices->value);
            $path = ConfigKey::LoggerServices->value . '.' . $serviceId;

            $result[$serviceId] = is_array($serviceConfig)
                ? $this->readLoggerServiceDefinition($serviceId, $serviceConfig)
                : new LoggerServiceDefinition(
                    serviceId: $serviceId,
                    channel: $this->nonEmptyString($serviceConfig, $path),
                );
        }

        return $result;
    }

    /**
     * @param array<string, mixed> $serviceConfig
     */
    private function readLoggerServiceDefinition(string $serviceId, array $serviceConfig): LoggerServiceDefinition
    {
        $configReader = ConfigReader::fromArray($serviceConfig, self::class);

        return new LoggerServiceDefinition(
            serviceId: $serviceId,
            channel: $configReader->requiredNonEmptyString(ConfigKey::Channel->value),
            name: $configReader->optionalNonEmptyString(ConfigKey::Name->value),
        );
    }

    /**
     * @return array<non-empty-string, ChannelDefinition>
     */
    private function readChannels(ConfigReader $configReader): array
    {
        $channels = $configReader->map(ConfigKey::Channels->value, [
            'default' => [
                ConfigKey::Name->value => 'app',
                ConfigKey::Handlers->value => ['default'],
            ],
        ]);

        /** @var array<non-empty-string, ChannelDefinition> $result */
        $result = [];

        foreach ($channels as $id => $channelConfig) {
            $id = $this->nonEmptyString($id, ConfigKey::Channels->value);
            $channelReader = ConfigReader::fromArray(
                $this->arrayValue($channelConfig, ConfigKey::Channels->value . '.' . $id),
                self::class,
            );

            $result[$id] = new ChannelDefinition(
                id: $id,
                name: $channelReader->nonEmptyString(ConfigKey::Name->value, $id),
                handlers: $channelReader->requiredNonEmptyStringList(ConfigKey::Handlers->value),
                processors: $channelReader->nonEmptyStringList(ConfigKey::Processors->value, []),
            );
        }

        return $result;
    }

    /**
     * @return array<non-empty-string, HandlerDefinition>
     */
    private function readHandlers(ConfigReader $configReader): array
    {
        $handlers = $configReader->map(ConfigKey::Handlers->value, [
            'default' => [
                ConfigKey::Type->value => HandlerType::Noop,
            ],
        ]);

        /** @var array<non-empty-string, HandlerDefinition> $result */
        $result = [];

        foreach ($handlers as $id => $handlerConfig) {
            $id = $this->nonEmptyString($id, ConfigKey::Handlers->value);
            $handlerReader = ConfigReader::fromArray(
                $this->arrayValue($handlerConfig, ConfigKey::Handlers->value . '.' . $id),
                self::class,
            );

            $result[$id] = new HandlerDefinition(
                id: $id,
                type: $this->typeValue(
                    $handlerReader->required(ConfigKey::Type->value),
                    ConfigKey::Handlers->value . '.' . $id,
                ),
                options: $handlerReader->map(ConfigKey::Options->value, []),
                formatter: $handlerReader->optionalNonEmptyString(ConfigKey::Formatter->value),
                processors: $handlerReader->nonEmptyStringList(ConfigKey::Processors->value, []),
            );
        }

        return $result;
    }

    /**
     * @return array<non-empty-string, FormatterDefinition>
     */
    private function readFormatters(ConfigReader $configReader): array
    {
        $formatters = $configReader->map(ConfigKey::Formatters->value, []);

        /** @var array<non-empty-string, FormatterDefinition> $result */
        $result = [];

        foreach ($formatters as $id => $formatterConfig) {
            $id = $this->nonEmptyString($id, ConfigKey::Formatters->value);
            $formatterReader = ConfigReader::fromArray(
                $this->arrayValue($formatterConfig, ConfigKey::Formatters->value . '.' . $id),
                self::class,
            );

            $result[$id] = new FormatterDefinition(
                id: $id,
                type: $this->typeValue(
                    $formatterReader->required(ConfigKey::Type->value),
                    ConfigKey::Formatters->value . '.' . $id,
                ),
                options: $formatterReader->map(ConfigKey::Options->value, []),
            );
        }

        return $result;
    }

    /**
     * @return array<non-empty-string, ProcessorDefinition>
     */
    private function readProcessors(ConfigReader $configReader): array
    {
        $processors = $configReader->map(ConfigKey::Processors->value, []);

        /** @var array<non-empty-string, ProcessorDefinition> $result */
        $result = [];

        foreach ($processors as $id => $processorConfig) {
            $id = $this->nonEmptyString($id, ConfigKey::Processors->value);
            $processorReader = ConfigReader::fromArray(
                $this->arrayValue($processorConfig, ConfigKey::Processors->value . '.' . $id),
                self::class,
            );

            $result[$id] = new ProcessorDefinition(
                id: $id,
                type: $this->typeValue(
                    $processorReader->required(ConfigKey::Type->value),
                    ConfigKey::Processors->value . '.' . $id,
                ),
                options: $processorReader->map(ConfigKey::Options->value, []),
            );
        }

        return $result;
    }

    private function readFactoryMap(ConfigReader $configReader): FactoryMap
    {
        return new FactoryMap(
            handlerFactories: $this->mergeFactoryMap(
                BuiltInHandlerFactories::map(),
                $configReader->map(ConfigKey::HandlerFactories->value, []),
                ConfigKey::HandlerFactories->value,
                HandlerFactoryInterface::class,
            ),
            formatterFactories: $this->mergeFactoryMap(
                BuiltInFormatterFactories::map(),
                $configReader->map(ConfigKey::FormatterFactories->value, []),
                ConfigKey::FormatterFactories->value,
                FormatterFactoryInterface::class,
            ),
            processorFactories: $this->mergeFactoryMap(
                BuiltInProcessorFactories::map(),
                $configReader->map(ConfigKey::ProcessorFactories->value, []),
                ConfigKey::ProcessorFactories->value,
                ProcessorFactoryInterface::class,
            ),
        );
    }

    /**
     * @template T of object
     *
     * @param array<non-empty-string, class-string<T>> $builtIn
     * @param array<string, mixed>                     $custom
     * @param class-string<T>                          $expectedInterface
     *
     * @return array<non-empty-string, class-string<T>>
     */
    private function mergeFactoryMap(array $builtIn, array $custom, string $path, string $expectedInterface): array
    {
        foreach ($custom as $type => $factoryClass) {
            $builtIn[$this->nonEmptyString($type, $path)] = $this->factoryClassString(
                $factoryClass,
                $path . '.' . $type,
                $expectedInterface,
            );
        }

        return $builtIn;
    }

    /**
     * @param array<non-empty-string, LoggerServiceDefinition> $loggerServices
     * @param array<non-empty-string, ChannelDefinition>       $channels
     */
    private function assertLoggerServiceReferences(array $loggerServices, array $channels): void
    {
        foreach ($loggerServices as $loggerService) {
            if (! isset($channels[$loggerService->channel])) {
                throw new MissingConfigException(
                    "Logger service '{$loggerService->serviceId}' references unknown channel "
                    . "'{$loggerService->channel}'.",
                );
            }
        }
    }

    /**
     * @param array<non-empty-string, ChannelDefinition>   $channels
     * @param array<non-empty-string, HandlerDefinition>   $handlers
     * @param array<non-empty-string, ProcessorDefinition> $processors
     */
    private function assertChannelReferences(array $channels, array $handlers, array $processors): void
    {
        foreach ($channels as $channel) {
            foreach ($channel->handlers as $handlerId) {
                if (! isset($handlers[$handlerId])) {
                    throw new MissingConfigException(
                        "Channel '{$channel->id}' references unknown handler '{$handlerId}'.",
                    );
                }
            }

            foreach ($channel->processors as $processorId) {
                if (! isset($processors[$processorId])) {
                    throw new MissingConfigException(
                        "Channel '{$channel->id}' references unknown processor '{$processorId}'.",
                    );
                }
            }
        }
    }

    /**
     * @param array<non-empty-string, HandlerDefinition>   $handlers
     * @param array<non-empty-string, FormatterDefinition> $formatters
     * @param array<non-empty-string, ProcessorDefinition> $processors
     */
    private function assertHandlerReferences(array $handlers, array $formatters, array $processors): void
    {
        foreach ($handlers as $handler) {
            if (null !== $handler->formatter && ! isset($formatters[$handler->formatter])) {
                throw new MissingConfigException(
                    "Handler '{$handler->id}' references unknown formatter '{$handler->formatter}'.",
                );
            }

            foreach ($handler->processors as $processorId) {
                if (! isset($processors[$processorId])) {
                    throw new MissingConfigException(
                        "Handler '{$handler->id}' references unknown processor '{$processorId}'.",
                    );
                }
            }
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function arrayValue(mixed $value, string $path): array
    {
        if (! is_array($value)) {
            throw new InvalidConfigException("Config value '{$path}' must be an array.");
        }

        return $value;
    }

    /**
     * @return non-empty-string
     */
    private function typeValue(mixed $value, string $path): string
    {
        if ($value instanceof BackedEnum) {
            return $this->nonEmptyString($value->value, $path . '.' . ConfigKey::Type->value);
        }

        return $this->nonEmptyString($value, $path . '.' . ConfigKey::Type->value);
    }

    /**
     * @return non-empty-string
     */
    private function nonEmptyString(mixed $value, string $path): string
    {
        if (! is_string($value)) {
            throw new InvalidConfigException("Config value '{$path}' must be a non-empty string.");
        }

        $value = trim($value);

        if ('' === $value) {
            throw new InvalidConfigException("Config value '{$path}' must be a non-empty string.");
        }

        return $value;
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $expectedInterface
     *
     * @return class-string<T>
     */
    private function factoryClassString(mixed $value, string $path, string $expectedInterface): string
    {
        $value = $this->nonEmptyString($value, $path);

        if (! class_exists($value)) {
            throw new InvalidConfigException("Config value '{$path}' must be an existing class-string.");
        }

        if (! is_a($value, $expectedInterface, true)) {
            throw new InvalidConfigException("Config value '{$path}' must implement {$expectedInterface}.");
        }

        return $value;
    }
}
