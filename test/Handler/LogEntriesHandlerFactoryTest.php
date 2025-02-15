<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Handler;

use Monolog\Handler\LogEntriesHandler;
use Monolog\Level;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Handler\LogEntriesHandlerFactory;

class LogEntriesHandlerFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $options = [
            'token' => 'token',
            'useSSL' => true,
            'level' => Level::Info,
            'bubble' => false,
        ];

        $factory = new LogEntriesHandlerFactory();
        $handler = $factory($options);

        $this->assertInstanceOf(LogEntriesHandler::class, $handler);
    }
}
