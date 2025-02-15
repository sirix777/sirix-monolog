<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Handler;

use Monolog\Handler\SocketHandler;
use Monolog\Level;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Handler\SocketHandlerFactory;

class SocketHandlerFactoryTest extends TestCase
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
