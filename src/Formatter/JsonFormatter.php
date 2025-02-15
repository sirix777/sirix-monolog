<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Monolog\Formatter\JsonFormatter as MonologJsonFormatter;
use Monolog\Formatter\NormalizerFormatter;
use Monolog\LogRecord;
use stdClass;

use function in_array;
use function is_array;

class JsonFormatter extends MonologJsonFormatter
{
    final public const BATCH_MODE_JSON = 1;
    final public const BATCH_MODE_NEWLINES = 2;

    public function __construct(
        protected int $batchMode = self::BATCH_MODE_JSON,
        protected bool $appendNewline = true,
        protected bool $ignoreEmptyContextAndExtra = false,
        protected bool $includeStacktraces = false,
        private readonly array $maskKeys = [],
    ) {
        parent::__construct(
            $this->batchMode,
            $this->appendNewline,
            $this->ignoreEmptyContextAndExtra,
            $this->includeStacktraces
        );
    }

    public function format(LogRecord $record): string
    {
        /** @noRector StaticMethodToNonStaticRector */
        $normalized = NormalizerFormatter::format($record);

        if ([] !== $this->maskKeys && [] !== $normalized['context'] && is_array($normalized['context'])) {
            $normalized['context'] = $this->maskRecord($normalized['context']);
        }

        if (isset($normalized['context']) && [] === $normalized['context']) {
            if ($this->ignoreEmptyContextAndExtra) {
                unset($normalized['context']);
            } else {
                $normalized['context'] = new stdClass();
            }
        }

        if (isset($normalized['extra']) && [] === $normalized['extra']) {
            if ($this->ignoreEmptyContextAndExtra) {
                unset($normalized['extra']);
            } else {
                $normalized['extra'] = new stdClass();
            }
        }

        return $this->toJson($normalized, true) . ($this->appendNewline ? "\n" : '');
    }

    private function maskRecord(array $context): array
    {
        foreach ($context as $key => &$value) {
            if (is_array($value)) {
                $value = $this->maskRecord($value);
            } elseif (in_array($key, $this->maskKeys, true)) {
                $value = '**[masked]**';
            }
        }

        return $context;
    }
}
