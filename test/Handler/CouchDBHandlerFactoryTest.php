<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Handler;

use Monolog\Handler\CouchDBHandler;
use Monolog\Level;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Handler\CouchDBHandlerFactory;

class CouchDBHandlerFactoryTest extends TestCase
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
