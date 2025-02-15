<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Processor;

use Monolog\Processor\HostnameProcessor;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Processor\HostnameProcessorFactory;

class HostnameProcessorFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $factory = new HostnameProcessorFactory();
        $handler = $factory([]);
        $this->assertInstanceOf(HostnameProcessor::class, $handler);
    }
}
