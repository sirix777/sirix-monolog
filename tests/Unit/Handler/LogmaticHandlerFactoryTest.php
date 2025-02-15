<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Handler;

use Sirix\Monolog\Handler\LogmaticHandlerFactory;
use Codeception\Test\Unit;
use Monolog\Handler\LogmaticHandler;
use Monolog\Handler\MissingExtensionException;
use Monolog\Level;

class LogmaticHandlerFactoryTest extends Unit
{
    /**
     * @throws MissingExtensionException
     */
    public function testInvoke()
    {
        $options = [
            'token' => 'some-token',
            'hostname' => 'some-host',
            'appname' => 'myApp',
            'useSSL' => false,
            'level' => Level::Debug,
            'bubble' => true,
        ];

        $factory = new LogmaticHandlerFactory();
        $handler = $factory($options);

        $this->assertInstanceOf(LogmaticHandler::class, $handler);
    }
}
