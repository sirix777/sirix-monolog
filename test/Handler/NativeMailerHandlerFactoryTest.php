<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Handler;

use Monolog\Handler\NativeMailerHandler;
use Monolog\Level;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Handler\NativeMailerHandlerFactory;

class NativeMailerHandlerFactoryTest extends TestCase
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
