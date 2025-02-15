<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Handler;

use Sirix\Monolog\Handler\NullHandlerFactory;
use Codeception\Test\Unit;
use Monolog\Handler\NullHandler;
use Monolog\Level;

class NullHandlerFactoryTest extends Unit
{
    public function testInvoke()
    {
        $options = [
            'level' => Level::Debug,
        ];

        $factory = new NullHandlerFactory();
        $handler = $factory($options);

        $this->assertInstanceOf(NullHandler::class, $handler);
    }
}
