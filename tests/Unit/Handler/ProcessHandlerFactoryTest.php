<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Handler;

use Sirix\Monolog\Handler\ProcessHandlerFactory;
use Codeception\Test\Unit;
use Monolog\Handler\ProcessHandler;
use Monolog\Level;

class ProcessHandlerFactoryTest extends Unit
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
