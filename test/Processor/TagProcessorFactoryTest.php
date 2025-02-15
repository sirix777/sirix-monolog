<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Processor;

use Monolog\Processor\TagProcessor;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Processor\TagProcessorFactory;

class TagProcessorFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $options = ['tags' => []];
        $factory = new TagProcessorFactory();
        $handler = $factory($options);
        $this->assertInstanceOf(TagProcessor::class, $handler);
    }
}
