<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Processor;

use Sirix\Monolog\Processor\PushoverDeviceProcessor;
use Sirix\Monolog\Processor\PushoverDeviceProcessorFactory;
use Codeception\Test\Unit;

class PushoverDeviceProcessorFactoryTest extends Unit
{
    public function testInvoke()
    {
        $options = ['device' => 'deviceName'];

        $factory = new PushoverDeviceProcessorFactory();
        $handler = $factory($options);
        $this->assertInstanceOf(PushoverDeviceProcessor::class, $handler);
    }
}
