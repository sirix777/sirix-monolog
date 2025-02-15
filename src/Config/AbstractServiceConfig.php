<?php

declare(strict_types=1);

namespace Sirix\Monolog\Config;

use Sirix\Monolog\ConfigInterface;
use Sirix\Monolog\Exception\MissingConfigException;

abstract class AbstractServiceConfig implements ConfigInterface
{
    protected array $config = [];

    public function __construct(array $config)
    {
        $this->validateConfig($config);
        $this->config = $config;
    }

    public function getType(): string
    {
        return $this->config['type'];
    }

    public function getOptions(): array
    {
        return $this->config['options'] ?? [];
    }

    protected function validateConfig(array $config): void
    {
        if ([] === $config) {
            throw new MissingConfigException(
                'No config found'
            );
        }

        if (empty($config['type'])) {
            throw new MissingConfigException(
                'No config key of "type" found in adaptor config array.'
            );
        }
    }
}
