<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Processor;

use Monolog\Processor\ProcessIdProcessor;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Processor\ProcessIdProcessorFactory;

class ProcessIdProcessorFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $factory = new ProcessIdProcessorFactory();
        $handler = $factory([]);
        $this->assertInstanceOf(ProcessIdProcessor::class, $handler);
    }
}
