<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Processor;

use Sirix\Monolog\Processor\MemoryUsageProcessorFactory;
use Codeception\Test\Unit;
use Monolog\Processor\MemoryUsageProcessor;

class MemoryUsageProcessorFactoryTest extends Unit
{
    public function testInvoke()
    {
        $factory = new MemoryUsageProcessorFactory();
        $handler = $factory([]);
        $this->assertInstanceOf(MemoryUsageProcessor::class, $handler);
    }
}
