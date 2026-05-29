<?php

declare(strict_types=1);

namespace Sirix\Monolog\Processor;

use Sirix\Monolog\Enum\ProcessorType;

final class BuiltInProcessorFactories
{
    /**
     * @return array<non-empty-string, class-string<ProcessorFactoryInterface>>
     */
    public static function map(): array
    {
        return [
            ProcessorType::PsrLogMessage->value => PsrLogMessageProcessorFactory::class,
            ProcessorType::Introspection->value => IntrospectionProcessorFactory::class,
            ProcessorType::Web->value => WebProcessorFactory::class,
            ProcessorType::MemoryUsage->value => MemoryUsageProcessorFactory::class,
            ProcessorType::MemoryPeakUsage->value => MemoryPeakUsageProcessorFactory::class,
            ProcessorType::ProcessId->value => ProcessIdProcessorFactory::class,
            ProcessorType::Uid->value => UidProcessorFactory::class,
            ProcessorType::Hostname->value => HostnameProcessorFactory::class,
            ProcessorType::Tags->value => TagProcessorFactory::class,
            ProcessorType::Redactor->value => RedactorProcessorFactory::class,
        ];
    }
}
