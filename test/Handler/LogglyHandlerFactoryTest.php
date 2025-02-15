<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Handler;

use Monolog\Handler\LogglyHandler;
use Monolog\Level;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Handler\LogglyHandlerFactory;

class LogglyHandlerFactoryTest extends TestCase
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
