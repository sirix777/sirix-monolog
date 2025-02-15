<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Monolog\Formatter\MongoDBFormatter;
use Sirix\Monolog\FactoryInterface;

/**
 * @SuppressWarnings("LongVariable")
 */
class MongoDBFormatterFactory implements FactoryInterface
{
    public function __invoke(array $options): MongoDBFormatter
    {
        $maxNestingLevel = (int) ($options['maxNestingLevel'] ?? 3);
        $exceptionTraceAsString = (bool) ($options['exceptionTraceAsString'] ?? true);

        return new MongoDBFormatter($maxNestingLevel, $exceptionTraceAsString);
    }
}
