<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Processor;

use Monolog\Processor\UidProcessor;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Processor\UidProcessorFactory;

class UidProcessorFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $options = ['length' => 7];

        $factory = new UidProcessorFactory();
        $handler = $factory($options);
        $this->assertInstanceOf(UidProcessor::class, $handler);
    }
}
