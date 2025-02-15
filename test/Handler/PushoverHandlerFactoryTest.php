<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Handler;

use Monolog\Handler\PushoverHandler;
use Monolog\Level;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Handler\PushoverHandlerFactory;

class PushoverHandlerFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $options = [
            'token' => 'sometokenhere',
            'users' => ['email1@test.com', 'email2@test.com'],
            'title' => 'Error Log',
            'level' => Level::Info,
            'bubble' => false,
            'useSSL' => false,
            'highPriorityLevel' => Level::Warning,
            'emergencyLevel' => Level::Error,
            'retry' => '22',
            'expire' => '300',
        ];

        $factory = new PushoverHandlerFactory();
        $handler = $factory($options);

        $this->assertInstanceOf(PushoverHandler::class, $handler);
    }
}
