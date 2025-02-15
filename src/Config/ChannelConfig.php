<?php

declare(strict_types=1);

namespace Sirix\Monolog\Config;

use Sirix\Monolog\Exception\MissingConfigException;

class ChannelConfig
{
    protected array $config = [];

    public function __construct(array $config)
    {
        $this->validateConfig($config);
        $this->config = $config;
    }

    public function getHandlers(): array
    {
        return $this->config['handlers'];
    }

    public function getProcessors(): array
    {
        return $this->config['processors'] ?? [];
    }

    public function getName(): ?string
    {
        return $this->config['name'] ?? null;
    }

    protected function validateConfig(array $config): void
    {
        if ([] === $config) {
            throw new MissingConfigException(
                'No config found'
            );
        }

        if (empty($config['handlers'])) {
            throw new MissingConfigException(
                'No config key of "handlers" found in channel config array.'
            );
        }
    }
}
