<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Handler;

use Sirix\Monolog\Handler\CouchDBHandlerFactory;
use Codeception\Test\Unit;
use Monolog\Handler\CouchDBHandler;
use Monolog\Level;

class CouchDBHandlerFactoryTest extends Unit
{
    public function testInvoke()
    {
        $options = [
            'host' => 'localhost',
            'port' => 5984,
            'dbname' => 'db',
            'username' => 'someuser',
            'password' => 'somepass',
            'level' => Level::Info,
            'bubble' => false,
        ];

        $factory = new CouchDBHandlerFactory();
        $handler = $factory($options);

        $this->assertInstanceOf(CouchDBHandler::class, $handler);
    }
}
