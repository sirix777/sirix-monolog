<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Processor;

use Sirix\Monolog\Processor\TagProcessorFactory;
use Codeception\Test\Unit;
use Monolog\Processor\TagProcessor;

class TagProcessorFactoryTest extends Unit
{
    public function testInvoke()
    {
        $options = ['tags' => []];
        $factory = new TagProcessorFactory();
        $handler = $factory($options);
        $this->assertInstanceOf(TagProcessor::class, $handler);
    }
}
