<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Handler;

use Sirix\Monolog\Handler\FlowdockHandlerFactory;
use Codeception\Test\Unit;
use Monolog\Handler\FlowdockHandler;
use Monolog\Level;

class FlowdockHandlerFactoryTest extends Unit
{
    public function testInvoke()
    {
        $options = [
            'apiToken' => 'sometokenhere',
            'level' => Level::Info,
            'bubble' => false,
        ];

        $factory = new FlowdockHandlerFactory();
        $handler = $factory($options);

        $this->assertInstanceOf(FlowdockHandler::class, $handler);
    }
}
