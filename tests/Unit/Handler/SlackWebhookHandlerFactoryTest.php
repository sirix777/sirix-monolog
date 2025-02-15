<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Handler;

use Sirix\Monolog\Handler\SlackWebhookHandlerFactory;
use Codeception\Test\Unit;
use Monolog\Handler\SlackWebhookHandler;
use Monolog\Level;

class SlackWebhookHandlerFactoryTest extends Unit
{
    public function testInvoke()
    {
        $options = [
            'webhookUrl' => 'webhook',
            'channel' => 'channel',
            'userName' => 'Monolog',
            'useAttachment' => false,
            'iconEmoji' => null,
            'useShortAttachment' => true,
            'includeContextAndExtra' => true,
            'level' => Level::Info,
            'bubble' => false,
            'excludeFields' => [],
        ];

        $factory = new SlackWebhookHandlerFactory();
        $handler = $factory($options);

        $this->assertInstanceOf(SlackWebhookHandler::class, $handler);
    }
}
