<?php

declare(strict_types=1);

namespace Sirix\Monolog\Config;

use Monolog\Level;
use Sirix\Monolog\Exception\MissingConfigException;

use function array_key_exists;

class MainConfig
{
    /** @var HandlerConfig[] */
    protected array $handlers = [];

    /** @var FormatterConfig[] */
    protected array $formatters = [];

    /** @var ChannelConfig[] */
    protected array $channels = [];

    /** @var ProcessorConfig[] */
    protected array $processors = [];

    public function __construct(array $config)
    {
        $this->setDefaults($config);
        $this->buildFormatters($config);
        $this->buildHandlers($config);
        $this->buildChannels($config);
        $this->buildProcessors($config);
    }

    /**
     * @return HandlerConfig[]
     */
    public function getHandlers(): array
    {
        return $this->handlers;
    }

    public function hasHandlerConfig(string $handler): bool
    {
        return array_key_exists($handler, $this->handlers);
    }

    public function getHandlerConfig(string $handler): ?HandlerConfig
    {
        return $this->handlers[$handler] ?? null;
    }

    /**
     * @return FormatterConfig[]
     */
    public function getFormatters(): array
    {
        return $this->formatters;
    }

    public function hasFormatterConfig(string $formatter): bool
    {
        return array_key_exists($formatter, $this->formatters);
    }

    public function getFormatterConfig(string $formatter): FormatterConfig
    {
        if (! $this->hasFormatterConfig($formatter)) {
            throw new MissingConfigException(
                'Unable to find formatter config'
            );
        }

        return $this->formatters[$formatter];
    }

    /**
     * @return ProcessorConfig[]
     */
    public function getProcessors(): array
    {
        return $this->processors;
    }

    public function hasProcessorConfig(string $processor): bool
    {
        return array_key_exists($processor, $this->processors);
    }

    public function getProcessorConfig(string $processor): ?ProcessorConfig
    {
        return $this->processors[$processor] ?? null;
    }

    /**
     * @return ChannelConfig[]
     */
    public function getChannels(): array
    {
        return $this->channels;
    }

    public function hasChannelConfig(string $channel): bool
    {
        return array_key_exists($channel, $this->channels);
    }

    public function getChannelConfig(string $channel): ?ChannelConfig
    {
        return $this->channels[$channel] ?? null;
    }

    protected function setDefaults(array &$config): void
    {
        if (empty($config['monolog']['handlers']['default'])) {
            $config['monolog']['handlers']['default'] = [
                'type' => 'noop',
                'options' => [
                    'level' => Level::Debug,
                ],
            ];
        }

        if (empty($config['monolog']['channels']['default'])) {
            $config['monolog']['channels']['default']['handlers'][] = 'default';
        }
    }

    protected function buildHandlers(array $config): void
    {
        foreach ($config['monolog']['handlers'] as $name => $handlerConfig) {
            $this->handlers[$name] = new HandlerConfig($handlerConfig);
        }
    }

    protected function buildChannels(array $config): void
    {
        foreach ($config['monolog']['channels'] as $name => $channelConfig) {
            $this->channels[$name] = new ChannelConfig($channelConfig);
        }
    }

    protected function buildFormatters(array $config): void
    {
        if (empty($config['monolog']['formatters'])) {
            return;
        }

        foreach ($config['monolog']['formatters'] as $name => $formatterConfig) {
            $this->formatters[$name] = new FormatterConfig($formatterConfig);
        }
    }

    protected function buildProcessors(array $config): void
    {
        if (empty($config['monolog']['processors'])) {
            return;
        }

        foreach ($config['monolog']['processors'] as $name => $processorConfig) {
            $this->processors[$name] = new ProcessorConfig($processorConfig);
        }
    }
}
