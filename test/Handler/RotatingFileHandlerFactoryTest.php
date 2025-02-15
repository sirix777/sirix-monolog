<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Handler;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Level;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Handler\RotatingFileHandlerFactory;

class RotatingFileHandlerFactoryTest extends TestCase
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
