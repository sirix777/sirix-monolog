<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Processor;

use Monolog\Processor\MemoryUsageProcessor;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Processor\MemoryUsageProcessorFactory;

class MemoryUsageProcessorFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $factory = new MemoryUsageProcessorFactory();
        $handler = $factory([]);
        $this->assertInstanceOf(MemoryUsageProcessor::class, $handler);
    }
}
