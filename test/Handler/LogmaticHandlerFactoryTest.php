<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Handler;

use Monolog\Handler\LogmaticHandler;
use Monolog\Handler\MissingExtensionException;
use Monolog\Level;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Handler\LogmaticHandlerFactory;

class LogmaticHandlerFactoryTest extends TestCase
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
