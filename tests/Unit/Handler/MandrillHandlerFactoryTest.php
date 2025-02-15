<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Handler;

use Sirix\Monolog\Handler\MandrillHandlerFactory;
use Codeception\Test\Unit;
use Monolog\Handler\MandrillHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Swift_Message;

class MandrillHandlerFactoryTest extends Unit
{
    public function testInvoke()
    {
        $options = [
            'apiKey' => 'my-api-key',
            'message' => 'my-message',
            'level' => Level::Debug,
            'bubble' => true,
        ];

        $mockContainer = $this->createMock(ContainerInterface::class);

        $mockMessage = $this->getMockBuilder(Swift_Message::class)
            ->disableOriginalConstructor()
            ->getMock();

        $hasMap = [
            ['my-message', true],
        ];

        $mockContainer->expects($this->once())
            ->method('has')
            ->will($this->returnValueMap($hasMap));

        $getMap = [
            ['my-message', $mockMessage],
        ];

        $mockContainer->expects($this->once())
            ->method('get')
            ->will($this->returnValueMap($getMap));

        $factory = new MandrillHandlerFactory();
        $factory->setContainer($mockContainer);

        $handler = $factory($options);

        $this->assertInstanceOf(MandrillHandler::class, $handler);
    }
}
