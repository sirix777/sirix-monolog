<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Handler;

use Monolog\Handler\BrowserConsoleHandler;
use Monolog\Level;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Handler\BrowserConsoleHandlerFactory;

class BrowserConsoleHandlerFactoryTest extends TestCase
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
