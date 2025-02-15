<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Handler;

use Sirix\Monolog\Handler\BrowserConsoleHandlerFactory;
use Codeception\Test\Unit;
use Monolog\Handler\BrowserConsoleHandler;
use Monolog\Level;

class BrowserConsoleHandlerFactoryTest extends Unit
{
    public function testInvoke()
    {
        $options = [
            'level' => Level::Info,
            'bubble' => false,
        ];

        $factory = new BrowserConsoleHandlerFactory();
        $handler = $factory($options);

        $this->assertInstanceOf(BrowserConsoleHandler::class, $handler);
    }
}
