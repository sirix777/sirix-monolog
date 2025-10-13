<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Monolog\Formatter\JsonFormatter;
use Sirix\Monolog\FactoryInterface;

class JsonFormatterFactory implements FactoryInterface
{
    public function __invoke(array $options): JsonFormatter
    {
        $batchMode = $options['batchMode'] ?? JsonFormatter::BATCH_MODE_JSON;
        $appendNewline = (bool) ($options['appendNewline'] ?? true);
        $ignoreEmptyContextAndExtra = (bool) ($options['ignoreEmptyContextAndExtra'] ?? false);
        $includeStacktraces = (bool) ($options['includeStacktraces'] ?? false);

        return new JsonFormatter(
            $batchMode,
            $appendNewline,
            $ignoreEmptyContextAndExtra,
            $includeStacktraces,
        );
    }
}
