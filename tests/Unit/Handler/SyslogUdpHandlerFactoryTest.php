<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Handler;

use Sirix\Monolog\Handler\SyslogUdpHandlerFactory;
use Codeception\Test\Unit;
use Monolog\Handler\SyslogUdpHandler;
use Monolog\Level;

class SyslogUdpHandlerFactoryTest extends Unit
{
    public function testInvoke()
    {
        $options = [
            'host' => 'somewhere.com',
            'port' => 513,
            'facility' => 'Me',
            'level' => Level::Info,
            'bubble' => false,
            'ident' => 'me-too',
        ];

        $factory = new SyslogUdpHandlerFactory();
        $handler = $factory($options);

        $this->assertInstanceOf(SyslogUdpHandler::class, $handler);
    }
}
