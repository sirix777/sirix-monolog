<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Handler;

use Sirix\Monolog\Handler\NoopHandlerFactory;
use Codeception\Test\Unit;
use Monolog\Handler\NoopHandler;

class NoopHandlerFactoryTest extends Unit
{
    public function testInvoke()
    {
        $factory = new NoopHandlerFactory();
        $handler = $factory([]);

        $this->assertInstanceOf(NoopHandler::class, $handler);
    }
}
