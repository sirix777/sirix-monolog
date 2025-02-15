<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Processor;

use Sirix\Monolog\Processor\MemoryPeakUsageProcessorFactory;
use Codeception\Test\Unit;
use Monolog\Processor\MemoryPeakUsageProcessor;

class MemoryPeakUsageProcessorFactoryTest extends Unit
{
    public function testInvoke()
    {
        $factory = new MemoryPeakUsageProcessorFactory();
        $handler = $factory([]);
        $this->assertInstanceOf(MemoryPeakUsageProcessor::class, $handler);
    }
}
