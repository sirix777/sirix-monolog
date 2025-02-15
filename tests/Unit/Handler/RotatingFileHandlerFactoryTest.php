<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Handler;

use Sirix\Monolog\Handler\RotatingFileHandlerFactory;
use Codeception\Test\Unit;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Level;

class RotatingFileHandlerFactoryTest extends Unit
{
    public function testInvoke()
    {
        $options = [
            'filename' => '/tmp/stream_test.txt',
            'maxFiles' => 0,
            'level' => Level::Debug,
            'bubble' => true,
            'filePermission' => null,
            'useLocking' => false,
        ];

        $factory = new RotatingFileHandlerFactory();
        $handler = $factory($options);

        $this->assertInstanceOf(RotatingFileHandler::class, $handler);
    }
}
