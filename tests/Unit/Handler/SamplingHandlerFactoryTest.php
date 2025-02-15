<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Handler;

use Sirix\Monolog\Exception\InvalidConfigException;
use Sirix\Monolog\Handler\SamplingHandlerFactory;
use Sirix\Monolog\Service\HandlerManager;
use Codeception\Test\Unit;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\SamplingHandler;

class SamplingHandlerFactoryTest extends Unit
{
    private SamplingHandlerFactory $factory;
    private object $mockHandlerManager;

    // @phpcs:ignore
    public function _before(): void
    {
        parent::_before();
        $this->factory = new SamplingHandlerFactory();

        $this->mockHandlerManager = $this->getMockBuilder(HandlerManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->factory->setHandlerManager($this->mockHandlerManager);
    }

    public function testInvoke()
    {
        $options = [
            'handler' => 'my-handler',
            'factor' => 5,
        ];

        $mockHandler = $this->createMock(HandlerInterface::class);

        $this->mockHandlerManager->expects($this->once())
            ->method('get')
            ->with($this->equalTo('my-handler'))
            ->willReturn($mockHandler);

        $handler = $this->factory->__invoke($options);

        $this->assertInstanceOf(SamplingHandler::class, $handler);
    }

    public function testInvokeErrorsWithNoFactor()
    {
        $this->expectException(InvalidConfigException::class);

        $options = [
            'handler' => 'my-handler',
        ];

        $mockHandler = $this->createMock(HandlerInterface::class);

        $this->mockHandlerManager->expects($this->once())
            ->method('get')
            ->with($this->equalTo('my-handler'))
            ->willReturn($mockHandler);

        $this->factory->__invoke($options);
    }
}
