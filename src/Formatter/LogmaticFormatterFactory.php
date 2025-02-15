<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Monolog\Formatter\JsonFormatter;
use Monolog\Formatter\LogmaticFormatter;
use Sirix\Monolog\FactoryInterface;

class LogmaticFormatterFactory implements FactoryInterface
{
    public function __invoke(array $options): LogmaticFormatter
    {
        $batchMode = $options['batchMode'] ?? JsonFormatter::BATCH_MODE_JSON;
        $appendNewline = (bool) ($options['appendNewline'] ?? true);
        $hostName = (string) ($options['hostname'] ?? '');
        $appName = (string) ($options['appName'] ?? '');

        $formatter = new LogmaticFormatter($batchMode, $appendNewline);

        if ('' !== $hostName && '0' !== $hostName) {
            $formatter->setHostname($hostName);
        }

        if ('' !== $appName && '0' !== $appName) {
            $formatter->setAppname($appName);
        }

        return $formatter;
    }
}
