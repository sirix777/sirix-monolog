<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Handler;

use Monolog\Handler\TelegramBotHandler;
use Monolog\Level;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Handler\TelegramBotHandlerFactory;

class TelegramBotHandlerFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $options = [
            'apiKey' => 'some-key',
            'channel' => 'some-channel',
            'level' => Level::Debug,
            'bubble' => true,
        ];

        $factory = new TelegramBotHandlerFactory();
        $handler = $factory($options);

        $this->assertInstanceOf(TelegramBotHandler::class, $handler);
    }
}
