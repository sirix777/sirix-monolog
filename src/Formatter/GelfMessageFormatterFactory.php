<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Monolog\Formatter\GelfMessageFormatter;
use Sirix\Monolog\FactoryInterface;

class GelfMessageFormatterFactory implements FactoryInterface
{
    public function __invoke(array $options): GelfMessageFormatter
    {
        $systemName = $options['systemName'] ?? null;
        $extraPrefix = $options['extraPrefix'] ?? null;
        $contextPrefix = $options['contextPrefix'] ?? 'ctxt_';
        $maxLength = $options['maxLength'] ?? null;

        return new GelfMessageFormatter($systemName, $extraPrefix, $contextPrefix, $maxLength);
    }
}
