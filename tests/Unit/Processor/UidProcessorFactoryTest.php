<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Processor;

use Sirix\Monolog\Processor\UidProcessorFactory;
use Codeception\Test\Unit;
use Monolog\Processor\UidProcessor;

class UidProcessorFactoryTest extends Unit
{
    public function testInvoke()
    {
        $options = ['length' => 7];

        $factory = new UidProcessorFactory();
        $handler = $factory($options);
        $this->assertInstanceOf(UidProcessor::class, $handler);
    }
}
