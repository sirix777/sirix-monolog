<?php

declare(strict_types=1);

namespace Sirix\Monolog\Processor;

use Sirix\Monolog\MapperInterface;

use function strtolower;

class ProcessorMapper implements MapperInterface
{
    public function map(string $type): ?string
    {
        $type = strtolower($type);

        return match ($type) {
            'psrlogmessage' => PsrLogMessageProcessorFactory::class,
            'introspection' => IntrospectionProcessorFactory::class,
            'web' => WebProcessorFactory::class,
            'memoryusage' => MemoryUsageProcessorFactory::class,
            'memorypeak' => MemoryPeakUsageProcessorFactory::class,
            'processid' => ProcessIdProcessorFactory::class,
            'uid' => UidProcessorFactory::class,
            'git' => GitProcessorFactory::class,
            'mercurial' => MercurialProcessorFactory::class,
            'tags' => TagProcessorFactory::class,
            'hostname' => HostnameProcessorFactory::class,
            'pushoverdevice' => PushoverDeviceProcessorFactory::class,
            default => null,
        };
    }
}
