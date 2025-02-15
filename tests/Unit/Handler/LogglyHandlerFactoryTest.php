<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Handler;

use Sirix\Monolog\Handler\LogglyHandlerFactory;
use Codeception\Test\Unit;
use Monolog\Handler\LogglyHandler;
use Monolog\Level;

class LogglyHandlerFactoryTest extends Unit
{
    public function testInvoke()
    {
        $options = [
            'token' => 'token',
            'level' => Level::Info,
            'bubble' => false,
        ];

        $factory = new LogglyHandlerFactory();
        $handler = $factory($options);

        $this->assertInstanceOf(LogglyHandler::class, $handler);
    }
}
