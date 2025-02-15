<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Processor;

use Sirix\Monolog\Processor\GitProcessorFactory;
use Codeception\Test\Unit;
use Monolog\Level;
use Monolog\Processor\GitProcessor;

class GitProcessorFactoryTest extends Unit
{
    public function testInvoke()
    {
        $options = ['level' => Level::Info];

        $factory = new GitProcessorFactory();
        $handler = $factory($options);
        $this->assertInstanceOf(GitProcessor::class, $handler);
    }
}
