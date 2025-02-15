<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Handler;

use Monolog\Handler\SlackWebhookHandler;
use Monolog\Level;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Handler\SlackWebhookHandlerFactory;

class SlackWebhookHandlerFactoryTest extends TestCase
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
