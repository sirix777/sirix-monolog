<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Handler;

use Sirix\Monolog\Handler\DeduplicationHandlerFactory;
use Sirix\Monolog\Service\HandlerManager;
use Codeception\Test\Unit;
use Monolog\Handler\DeduplicationHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Level;

class DeduplicationHandlerFactoryTest extends Unit
{
    private DeduplicationHandlerFactory $factory;
    private object $mockHandlerManager;

    // @phpcs:ignore
    public function _before(): void
    {
        parent::_before();
        $this->factory = new DeduplicationHandlerFactory();

        $this->mockHandlerManager = $this->getMockBuilder(HandlerManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->factory->setHandlerManager($this->mockHandlerManager);
    }

    public function testInvoke()
    {
        $options = [
            'handler' => 'my-handler',
            'deduplicationStore' => '/tmp/store',
            'deduplicationLevel' => Level::Debug,
            'time' => 2,
        ];

        $mockHandler = $this->createMock(HandlerInterface::class);

        $this->mockHandlerManager->expects($this->once())
            ->method('get')
            ->with($this->equalTo('my-handler'))
            ->willReturn($mockHandler);

        $handler = $this->factory->__invoke($options);

        $this->assertInstanceOf(DeduplicationHandler::class, $handler);
    }
}
