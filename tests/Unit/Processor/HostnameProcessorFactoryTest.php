<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Processor;

use Sirix\Monolog\Processor\HostnameProcessorFactory;
use Codeception\Test\Unit;
use Monolog\Processor\HostnameProcessor;

class HostnameProcessorFactoryTest extends Unit
{
    public function testInvoke()
    {
        $factory = new HostnameProcessorFactory();
        $handler = $factory([]);
        $this->assertInstanceOf(HostnameProcessor::class, $handler);
    }
}
