<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Processor;

use Sirix\Monolog\Processor\IntrospectionProcessorFactory;
use Codeception\Test\Unit;
use Monolog\Level;
use Monolog\Processor\IntrospectionProcessor;

class IntrospectionProcessorFactoryTest extends Unit
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
