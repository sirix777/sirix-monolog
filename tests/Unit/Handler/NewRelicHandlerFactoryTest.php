<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Handler;

use Sirix\Monolog\Handler\NewRelicHandlerFactory;
use Codeception\Test\Unit;
use Monolog\Handler\NewRelicHandler;
use Monolog\Level;

class NewRelicHandlerFactoryTest extends Unit
{
    public function testInvoke()
    {
        $options = [
            'level' => Level::Info,
            'bubble' => false,
            'appName' => 'myApp',
            'explodeArrays' => false,
            'transactionName' => 'some-transaction',
        ];

        $factory = new NewRelicHandlerFactory();
        $handler = $factory($options);

        $this->assertInstanceOf(NewRelicHandler::class, $handler);
    }
}
