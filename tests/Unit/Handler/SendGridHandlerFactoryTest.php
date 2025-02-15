<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Handler;

use Sirix\Monolog\Handler\SendGridHandlerFactory;
use Codeception\Test\Unit;
use Monolog\Handler\SendGridHandler;
use Monolog\Level;

class SendGridHandlerFactoryTest extends Unit
{
    public function testInvoke()
    {
        $options = [
            'apiUser' => 'some-user',
            'apiKey' => 'some-key',
            'from' => 'me@me.com',
            'to' => 'someone@here.com',
            'subject' => 'monolog',
            'level' => Level::Debug,
            'bubble' => true,
        ];

        $factory = new SendGridHandlerFactory();
        $handler = $factory($options);

        $this->assertInstanceOf(SendGridHandler::class, $handler);
    }
}
