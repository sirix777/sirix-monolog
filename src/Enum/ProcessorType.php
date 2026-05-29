<?php

declare(strict_types=1);

namespace Sirix\Monolog\Enum;

enum ProcessorType: string
{
    case PsrLogMessage = 'psr_log_message';
    case Introspection = 'introspection';
    case Web = 'web';
    case MemoryUsage = 'memory_usage';
    case MemoryPeakUsage = 'memory_peak_usage';
    case ProcessId = 'process_id';
    case Uid = 'uid';
    case Hostname = 'hostname';
    case Tags = 'tags';
    case Redactor = 'redactor';
}
