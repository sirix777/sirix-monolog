<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Processor;

use Monolog\Processor\PsrLogMessageProcessor;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Processor\PsrLogMessageProcessorFactory;

class PsrLogMessageProcessorFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $factory = new PsrLogMessageProcessorFactory();
        $handler = $factory([]);
        $this->assertInstanceOf(PsrLogMessageProcessor::class, $handler);
    }
}
