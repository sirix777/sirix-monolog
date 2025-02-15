<?php

declare(strict_types=1);

namespace Sirix\Monolog\Processor;

use Monolog\Processor\MemoryPeakUsageProcessor;
use Sirix\Monolog\FactoryInterface;

class MemoryPeakUsageProcessorFactory implements FactoryInterface
{
    public function __invoke(array $options): MemoryPeakUsageProcessor
    {
        return new MemoryPeakUsageProcessor();
    }
}
