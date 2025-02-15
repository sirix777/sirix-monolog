<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Handler;

use Sirix\Monolog\Handler\TelegramBotHandlerFactory;
use Codeception\Test\Unit;
use Monolog\Handler\TelegramBotHandler;
use Monolog\Level;

class TelegramBotHandlerFactoryTest extends Unit
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
