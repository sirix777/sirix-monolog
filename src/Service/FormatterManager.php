<?php

declare(strict_types=1);

namespace Sirix\Monolog\Service;

use Sirix\Monolog\Config\FormatterConfig;

class FormatterManager extends AbstractServiceManager
{
    public function getServiceConfig(string $id): FormatterConfig
    {
        return $this->config->getFormatterConfig($id);
    }

    public function hasServiceConfig(string $id): bool
    {
        return $this->config->hasFormatterConfig($id);
    }
}
