<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Processor;

use Monolog\Processor\MemoryPeakUsageProcessor;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Processor\MemoryPeakUsageProcessorFactory;

class MemoryPeakUsageProcessorFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $factory = new MemoryPeakUsageProcessorFactory();
        $handler = $factory([]);
        $this->assertInstanceOf(MemoryPeakUsageProcessor::class, $handler);
    }
}
