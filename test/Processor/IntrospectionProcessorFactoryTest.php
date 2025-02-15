<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Processor;

use Monolog\Level;
use Monolog\Processor\IntrospectionProcessor;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Processor\IntrospectionProcessorFactory;

class IntrospectionProcessorFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $options = [
            'level' => Level::Info,
            'skipClassesPartials' => [],
            'skipStackFramesCount' => 0,
        ];

        $factory = new IntrospectionProcessorFactory();
        $handler = $factory($options);
        $this->assertInstanceOf(IntrospectionProcessor::class, $handler);
    }
}
