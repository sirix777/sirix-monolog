<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Handler;

use Monolog\Handler\NewRelicHandler;
use Monolog\Level;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Handler\NewRelicHandlerFactory;

class NewRelicHandlerFactoryTest extends TestCase
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
