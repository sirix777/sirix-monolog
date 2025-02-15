<?php

declare(strict_types=1);

namespace Sirix\Monolog\Config;

class HandlerConfig extends AbstractServiceConfig
{
    public function getFormatter(): string
    {
        return $this->config['formatter'] ?? '';
    }

    public function getProcessors(): array
    {
        return $this->config['processors'] ?? [];
    }
}
