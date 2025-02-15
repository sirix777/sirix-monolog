<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Handler;

use Monolog\Handler\IFTTTHandler;
use Monolog\Level;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Handler\IFTTTHandlerFactory;

class IFTTTHandlerFactoryTest extends TestCase
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
