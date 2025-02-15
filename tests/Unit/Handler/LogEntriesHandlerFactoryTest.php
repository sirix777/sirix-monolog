<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Handler;

use Sirix\Monolog\Handler\LogEntriesHandlerFactory;
use Codeception\Test\Unit;
use Monolog\Handler\LogEntriesHandler;
use Monolog\Level;

class LogEntriesHandlerFactoryTest extends Unit
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
