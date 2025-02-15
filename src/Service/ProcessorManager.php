<?php

declare(strict_types=1);

namespace Sirix\Monolog\Service;

use Sirix\Monolog\ConfigInterface;

class ProcessorManager extends AbstractServiceManager
{
    public function getServiceConfig(string $id): ?ConfigInterface
    {
        return $this->config->getProcessorConfig($id);
    }

    public function hasServiceConfig(string $id): bool
    {
        return $this->config->hasProcessorConfig($id);
    }
}
