<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Handler;

use Monolog\Handler\FlowdockHandler;
use Monolog\Level;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Handler\FlowdockHandlerFactory;

class FlowdockHandlerFactoryTest extends TestCase
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
