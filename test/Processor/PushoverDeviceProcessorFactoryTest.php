<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Processor;

use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Processor\PushoverDeviceProcessor;
use Sirix\Monolog\Processor\PushoverDeviceProcessorFactory;

class PushoverDeviceProcessorFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $options = ['device' => 'deviceName'];

        $factory = new PushoverDeviceProcessorFactory();
        $handler = $factory($options);
        $this->assertInstanceOf(PushoverDeviceProcessor::class, $handler);
    }
}
