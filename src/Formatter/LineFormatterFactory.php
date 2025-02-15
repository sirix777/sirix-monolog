<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Monolog\Formatter\LineFormatter;
use Sirix\Monolog\FactoryInterface;

/**
 * @SuppressWarnings("LongVariable")
 */
class LineFormatterFactory implements FactoryInterface
{
    public function __invoke(array $options): LineFormatter
    {
        $format = $options['format'] ?? null;
        $dateFormat = $options['dateFormat'] ?? null;
        $allowInlineLineBreaks = (bool) ($options['allowInlineLineBreaks'] ?? false);
        $ignoreEmptyContextAndExtra = (bool) ($options['ignoreEmptyContextAndExtra'] ?? false);

        return new LineFormatter(
            $format,
            $dateFormat,
            $allowInlineLineBreaks,
            $ignoreEmptyContextAndExtra
        );
    }
}
