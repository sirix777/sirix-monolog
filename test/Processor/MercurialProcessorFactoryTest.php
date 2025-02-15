<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Processor;

use Monolog\Level;
use Monolog\Processor\MercurialProcessor;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Processor\MercurialProcessorFactory;

class MercurialProcessorFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $options = ['level' => Level::Info];

        $factory = new MercurialProcessorFactory();
        $handler = $factory($options);
        $this->assertInstanceOf(MercurialProcessor::class, $handler);
    }
}
