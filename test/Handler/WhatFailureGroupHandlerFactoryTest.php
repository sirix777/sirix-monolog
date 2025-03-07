<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Handler;

use Monolog\Handler\HandlerInterface;
use Monolog\Handler\WhatFailureGroupHandler;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Exception\MissingConfigException;
use Sirix\Monolog\Handler\WhatFailureGroupHandlerFactory;
use Sirix\Monolog\Service\HandlerManager;

class WhatFailureGroupHandlerFactoryTest extends TestCase
{
    private WhatFailureGroupHandlerFactory $factory;
    private object $mockHandlerManager;

    public function setUp(): void
    {
        $this->factory = new WhatFailureGroupHandlerFactory();

        $this->mockHandlerManager = $this->getMockBuilder(HandlerManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->factory->setHandlerManager($this->mockHandlerManager);
    }

    public function testInvoke()
    {
        $options = [
            'handlers' => [
                'my-handler-one',
                'my-handler-two',
            ],
            'bubble' => true,
        ];

        $mockHandler = $this->createMock(HandlerInterface::class);

        $map = [
            ['my-handler-one', $mockHandler],
            ['my-handler-two', $mockHandler],
        ];

        $this->mockHandlerManager->expects($this->exactly(2))
            ->method('get')
            ->willReturnMap($map)
        ;

        $handler = $this->factory->__invoke($options);

        $this->assertInstanceOf(WhatFailureGroupHandler::class, $handler);
    }

    public function testInvokeMissingHandlers()
    {
        $this->expectException(MissingConfigException::class);

        $options = [
            'handlers' => [],
            'bubble' => true,
        ];

        $this->mockHandlerManager->expects($this->never())
            ->method('get')
        ;

        $this->factory->__invoke($options);
    }

    public function testInvokeMissingHandlersKey()
    {
        $this->expectException(MissingConfigException::class);

        $options = [
            'bubble' => true,
        ];

        $this->mockHandlerManager->expects($this->never())
            ->method('get')
        ;

        $this->factory->__invoke($options);
    }
}
