<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Handler;

use Monolog\Handler\HandlerInterface;
use Monolog\Handler\OverflowHandler;
use Monolog\Level;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Exception\UnknownServiceException;
use Sirix\Monolog\Handler\OverflowHandlerFactory;
use Sirix\Monolog\Service\HandlerManager;

class OverflowHandlerFactoryTest extends TestCase
{
    private OverflowHandlerFactory $factory;
    private object $mockHandlerManager;

    public function setUp(): void
    {
        $this->factory = new OverflowHandlerFactory();

        $this->mockHandlerManager = $this->getMockBuilder(HandlerManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->factory->setHandlerManager($this->mockHandlerManager);
    }

    public function testInvoke()
    {
        $options = [
            'handler' => 'my-handler-one',
            'thresholdMap' => [
                'debug' => 2,
                'info' => 2,
                'notice' => 2,
                'warning' => 2,
                'error' => 2,
                'critical' => 2,
                'alert' => 2,
                'emergency' => 2,
            ],
            'level' => Level::Debug,
            'bubble' => true,
        ];

        $mockHandler = $this->createMock(HandlerInterface::class);

        $this->mockHandlerManager->expects($this->once())
            ->method('get')
            ->with($this->equalTo('my-handler-one'))
            ->willReturn($mockHandler)
        ;

        $handler = $this->factory->__invoke($options);

        $this->assertInstanceOf(OverflowHandler::class, $handler);
    }

    public function testInvokeMissingHandlers()
    {
        $this->expectException(UnknownServiceException::class);

        $options = [
            'handler' => 'my-handler-two',
            'thresholdMap' => [
                'debug' => 2,
                'info' => 2,
                'notice' => 2,
                'warning' => 2,
                'error' => 2,
                'critical' => 2,
                'alert' => 2,
                'emergency' => 2,
            ],
            'level' => Level::Debug,
            'bubble' => true,
        ];

        $this->mockHandlerManager->expects($this->once())
            ->method('get')
            ->with($this->equalTo('my-handler-two'))
            ->willThrowException(new UnknownServiceException('Unit test'))
        ;

        $this->factory->__invoke($options);
    }
}
