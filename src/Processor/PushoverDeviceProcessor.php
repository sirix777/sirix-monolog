<?php

declare(strict_types=1);

namespace Sirix\Monolog\Processor;

use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;

class PushoverDeviceProcessor implements ProcessorInterface
{
    public function __construct(private readonly ?string $device = null) {}

    public function __invoke(LogRecord $record): LogRecord
    {
        if (isset($this->device)) {
            $record->extra['device'] = $this->device;
        }

        return $record;
    }
}
