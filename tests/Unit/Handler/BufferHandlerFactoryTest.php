<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Handler;

use Sirix\Monolog\Handler\BufferHandlerFactory;
use Sirix\Monolog\Service\HandlerManager;
use Codeception\Test\Unit;
use Monolog\Handler\BufferHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Level;

class BufferHandlerFactoryTest extends Unit
{
    private BufferHandlerFactory $factory;
    private object $mockHandlerManager;

    // @phpcs:ignore
    public function _before(): void
    {
        parent::_before();
        $this->factory = new BufferHandlerFactory();

        $this->mockHandlerManager = $this->getMockBuilder(HandlerManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->factory->setHandlerManager($this->mockHandlerManager);
    }

    public function testInvoke()
    {
        $options = [
            'handler' => 'my-handler',
            'bufferLimit' => 4,
            'level' => Level::Debug,
            'bubble' => true,
            'flushOnOverflow' => false,
        ];

        $mockHandler = $this->createMock(HandlerInterface::class);

        $this->mockHandlerManager->expects($this->once())
            ->method('get')
            ->with($this->equalTo('my-handler'))
            ->willReturn($mockHandler);

        $handler = $this->factory->__invoke($options);

        $this->assertInstanceOf(BufferHandler::class, $handler);
    }
}
