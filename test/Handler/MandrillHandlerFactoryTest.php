<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Handler;

use Monolog\Handler\MandrillHandler;
use Monolog\Level;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Sirix\Monolog\Handler\MandrillHandlerFactory;
use Swift_Message;

class MandrillHandlerFactoryTest extends TestCase
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
            ->getMock()
        ;

        $hasMap = [
            ['my-message', true],
        ];

        $mockContainer->expects($this->once())
            ->method('has')
            ->willReturnMap($hasMap)
        ;

        $getMap = [
            ['my-message', $mockMessage],
        ];

        $mockContainer->expects($this->once())
            ->method('get')
            ->willReturnMap($getMap)
        ;

        $factory = new MandrillHandlerFactory();
        $factory->setContainer($mockContainer);

        $handler = $factory($options);

        $this->assertInstanceOf(MandrillHandler::class, $handler);
    }
}
