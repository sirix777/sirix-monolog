<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Monolog\Formatter\LogstashFormatter;
use Sirix\Monolog\FactoryInterface;

class LogstashFormatterFactory implements FactoryInterface
{
    public function __invoke(array $options): LogstashFormatter
    {
        $applicationName = $options['applicationName'] ?? '';
        $systemName = $options['systemName'] ?? '';
        $extraPrefix = $options['extraPrefix'] ?? '';
        $contextPrefix = (string) ($options['contextPrefix'] ?? 'ctxt_');

        return new LogstashFormatter($applicationName, $systemName, $extraPrefix, $contextPrefix);
    }
}
