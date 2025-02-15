<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Processor;

use Sirix\Monolog\Processor\PsrLogMessageProcessorFactory;
use Codeception\Test\Unit;
use Monolog\Processor\PsrLogMessageProcessor;

class PsrLogMessageProcessorFactoryTest extends Unit
{
    public function testInvoke()
    {
        $factory = new PsrLogMessageProcessorFactory();
        $handler = $factory([]);
        $this->assertInstanceOf(PsrLogMessageProcessor::class, $handler);
    }
}
