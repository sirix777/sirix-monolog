<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Handler;

use Monolog\Handler\ErrorLogHandler;
use Monolog\Level;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Handler\ErrorLogHandlerFactory;

class ErrorLogHandlerFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $options = [
            'messageType' => ErrorLogHandler::OPERATING_SYSTEM,
            'level' => Level::Debug,
            'bubble' => true,
            'expandNewlines' => false,
        ];

        $factory = new ErrorLogHandlerFactory();
        $handler = $factory($options);

        $this->assertInstanceOf(ErrorLogHandler::class, $handler);
    }
}
