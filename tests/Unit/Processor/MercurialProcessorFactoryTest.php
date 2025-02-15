<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Processor;

use Sirix\Monolog\Processor\MercurialProcessorFactory;
use Codeception\Test\Unit;
use Monolog\Level;
use Monolog\Processor\MercurialProcessor;

class MercurialProcessorFactoryTest extends Unit
{
    public function testInvoke()
    {
        $options = ['level' => Level::Info];

        $factory = new MercurialProcessorFactory();
        $handler = $factory($options);
        $this->assertInstanceOf(MercurialProcessor::class, $handler);
    }
}
