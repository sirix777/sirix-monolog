<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Handler;

use Monolog\Handler\NoopHandler;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Handler\NoopHandlerFactory;

class NoopHandlerFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $factory = new NoopHandlerFactory();
        $handler = $factory([]);

        $this->assertInstanceOf(NoopHandler::class, $handler);
    }
}
