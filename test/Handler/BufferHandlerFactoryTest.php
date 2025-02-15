<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Handler;

use Monolog\Handler\BufferHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Level;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Handler\BufferHandlerFactory;
use Sirix\Monolog\Service\HandlerManager;

class BufferHandlerFactoryTest extends TestCase
{
    private BufferHandlerFactory $factory;
    private object $mockHandlerManager;

    // @phpcs:ignore
    public function setUp(): void
    {
        $this->factory = new BufferHandlerFactory();

        $this->mockHandlerManager = $this->getMockBuilder(HandlerManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

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
            ->willReturn($mockHandler)
        ;

        $handler = $this->factory->__invoke($options);

        $this->assertInstanceOf(BufferHandler::class, $handler);
    }
}
