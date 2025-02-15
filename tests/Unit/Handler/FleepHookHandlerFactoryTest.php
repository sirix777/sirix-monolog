<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Handler;

use Sirix\Monolog\Handler\FleepHookHandlerFactory;
use Codeception\Test\Unit;
use Monolog\Handler\FleepHookHandler;
use Monolog\Level;

class FleepHookHandlerFactoryTest extends Unit
{
    public function testInvoke()
    {
        $options = [
            'token' => 'token',
            'level' => Level::Info,
            'bubble' => false,
        ];

        $factory = new FleepHookHandlerFactory();
        $handler = $factory($options);

        $this->assertInstanceOf(FleepHookHandler::class, $handler);
    }
}
