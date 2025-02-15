<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Processor;

use Sirix\Monolog\Processor\ProcessIdProcessorFactory;
use Codeception\Test\Unit;
use Monolog\Processor\ProcessIdProcessor;

class ProcessIdProcessorFactoryTest extends Unit
{
    public function testInvoke()
    {
        $factory = new ProcessIdProcessorFactory();
        $handler = $factory([]);
        $this->assertInstanceOf(ProcessIdProcessor::class, $handler);
    }
}
