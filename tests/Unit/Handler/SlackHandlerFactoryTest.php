<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Handler;

use Sirix\Monolog\Handler\SlackHandlerFactory;
use Codeception\Test\Unit;
use Monolog\Handler\SlackHandler;
use Monolog\Level;

class SlackHandlerFactoryTest extends Unit
{
    public function testInvoke()
    {
        $options = [
            'token' => 'webhook',
            'channel' => 'channel',
            'userName' => 'Monolog',
            'useAttachment' => false,
            'iconEmoji' => null,
            'level' => Level::Info,
            'bubble' => false,
            'useShortAttachment' => true,
            'includeContextAndExtra' => true,
            'excludeFields' => [],
        ];

        $factory = new SlackHandlerFactory();
        $handler = $factory($options);

        $this->assertInstanceOf(SlackHandler::class, $handler);
    }
}
