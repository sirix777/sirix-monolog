<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Processor;

use Monolog\Level;
use Monolog\Processor\GitProcessor;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Processor\GitProcessorFactory;

class GitProcessorFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $options = ['level' => Level::Info];

        $factory = new GitProcessorFactory();
        $handler = $factory($options);
        $this->assertInstanceOf(GitProcessor::class, $handler);
    }
}
