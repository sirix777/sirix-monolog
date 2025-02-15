<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Monolog\Formatter\LogglyFormatter;
use Sirix\Monolog\FactoryInterface;

class LogglyFormatterFactory implements FactoryInterface
{
    public function __invoke(array $options): LogglyFormatter
    {
        $batchMode = $options['batchMode'] ?? LogglyFormatter::BATCH_MODE_NEWLINES;
        $appendNewline = (bool) ($options['appendNewline'] ?? true);

        return new LogglyFormatter($batchMode, $appendNewline);
    }
}
