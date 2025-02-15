<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Handler;

use Monolog\Handler\SlackHandler;
use Monolog\Level;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Handler\SlackHandlerFactory;

class SlackHandlerFactoryTest extends TestCase
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
