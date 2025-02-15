<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Handler;

use Sirix\Monolog\Handler\NativeMailerHandlerFactory;
use Codeception\Test\Unit;
use Monolog\Handler\NativeMailerHandler;
use Monolog\Level;

class NativeMailerHandlerFactoryTest extends Unit
{
    public function testInvoke()
    {
        $options = [
            'to' => ['email1@test.com', 'email2@test.com'],
            'subject' => 'Error Log',
            'from' => 'sender@test.com',
            'level' => Level::Debug,
            'bubble' => true,
            'maxColumnWidth' => 80,
        ];

        $factory = new NativeMailerHandlerFactory();
        $handler = $factory($options);

        $this->assertInstanceOf(NativeMailerHandler::class, $handler);
    }

    public function testInvokeStringTo()
    {
        $options = [
            'to' => 'email1@test.com',
            'subject' => 'Error Log',
            'from' => 'sender@test.com',
            'level' => Level::Debug,
            'bubble' => true,
            'maxColumnWidth' => 80,
        ];

        $factory = new NativeMailerHandlerFactory();
        $handler = $factory($options);

        $this->assertInstanceOf(NativeMailerHandler::class, $handler);
    }
}
