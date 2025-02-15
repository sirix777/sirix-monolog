<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Handler;

use Sirix\Monolog\Handler\FilterHandlerFactory;
use Sirix\Monolog\Service\HandlerManager;
use Codeception\Test\Unit;
use Monolog\Handler\FilterHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Level;

class FilterHandlerFactoryTest extends Unit
{
    private FilterHandlerFactory $factory;
    private object $mockHandlerManager;

    // @phpcs:ignore
    public function _before(): void
    {
        parent::_before();
        $this->factory = new FilterHandlerFactory();

        $this->mockHandlerManager = $this->getMockBuilder(HandlerManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->factory->setHandlerManager($this->mockHandlerManager);
    }

    public function testInvoke()
    {
        $options = [
            'handler' => 'my-handler',
            'minLevelOrList' => Level::Debug,
            'maxLevel' => Level::Debug,
            'bubble' => true,
        ];

        $mockHandler = $this->createMock(HandlerInterface::class);

        $this->mockHandlerManager->expects($this->once())
            ->method('get')
            ->with($this->equalTo('my-handler'))
            ->willReturn($mockHandler);

        $handler = $this->factory->__invoke($options);

        $this->assertInstanceOf(FilterHandler::class, $handler);
    }
}
