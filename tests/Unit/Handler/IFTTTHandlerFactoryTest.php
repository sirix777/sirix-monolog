<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Handler;

use Sirix\Monolog\Handler\IFTTTHandlerFactory;
use Codeception\Test\Unit;
use Monolog\Handler\IFTTTHandler;
use Monolog\Level;

class IFTTTHandlerFactoryTest extends Unit
{
    public function testInvoke()
    {
        $options = [
            'eventName' => 'event',
            'secretKey' => 'my-secret',
            'level' => Level::Info,
            'bubble' => false,
        ];

        $factory = new IFTTTHandlerFactory();
        $handler = $factory($options);

        $this->assertInstanceOf(IFTTTHandler::class, $handler);
    }
}
