<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Handler;

use Monolog\Handler\SyslogUdpHandler;
use Monolog\Level;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Handler\SyslogUdpHandlerFactory;

class SyslogUdpHandlerFactoryTest extends TestCase
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
