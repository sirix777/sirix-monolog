<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Handler;

use Sirix\Monolog\Handler\ErrorLogHandlerFactory;
use Codeception\Test\Unit;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Level;

class ErrorLogHandlerFactoryTest extends Unit
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
