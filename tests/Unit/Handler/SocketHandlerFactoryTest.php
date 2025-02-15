<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Handler;

use Sirix\Monolog\Handler\SocketHandlerFactory;
use Codeception\Test\Unit;
use Monolog\Handler\SocketHandler;
use Monolog\Level;

class SocketHandlerFactoryTest extends Unit
{
    public function testInvoke()
    {
        $options = [
            'connectionString' => 'connect',
            'timeout' => 300,
            'writeTimeout' => 900,
            'level' => Level::Info,
            'bubble' => false,
        ];

        $factory = new SocketHandlerFactory();
        $handler = $factory($options);

        $this->assertInstanceOf(SocketHandler::class, $handler);
        $this->assertEquals($options['timeout'], $handler->getConnectionTimeout());
        $this->assertEquals($options['writeTimeout'], $handler->getWritingTimeout());
        $this->assertEquals($options['writeTimeout'], $handler->getTimeout());
    }
}
