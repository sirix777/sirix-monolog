<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Handler;

use Monolog\Handler\ProcessHandler;
use Monolog\Level;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Handler\ProcessHandlerFactory;

class ProcessHandlerFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $options = [
            'command' => 'some-command',
            'level' => Level::Debug,
            'bubble' => true,
            'cwd' => __DIR__,
        ];

        $factory = new ProcessHandlerFactory();
        $handler = $factory($options);

        $this->assertInstanceOf(ProcessHandler::class, $handler);
    }
}
