<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Handler;

use Monolog\Handler\SendGridHandler;
use Monolog\Level;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Handler\SendGridHandlerFactory;

class SendGridHandlerFactoryTest extends TestCase
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
