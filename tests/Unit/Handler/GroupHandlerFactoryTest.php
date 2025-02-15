<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Handler;

use Sirix\Monolog\Handler\GelfHandlerFactory;
use Codeception\Test\Unit;
use Gelf\PublisherInterface;
use Monolog\Handler\GelfHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;

class GroupHandlerFactoryTest extends Unit
{
    private GelfHandlerFactory $factory;
    private object $mockContainer;

    // @phpcs:ignore
    public function _before(): void
    {
        parent::_before();
        $this->factory = new GelfHandlerFactory();
        $this->mockContainer = $this->createMock(ContainerInterface::class);
        $this->factory->setContainer($this->mockContainer);
    }

    public function testInvoke()
    {
        $options = [
            'publisher' => 'my-service',
            'level' => Level::Info,
            'bubble' => false,
        ];

        $mockService = $this->createMock(PublisherInterface::class);

        $this->mockContainer->expects($this->once())
            ->method('has')
            ->with($this->equalTo('my-service'))
            ->willReturn(true);

        $this->mockContainer->expects($this->once())
            ->method('get')
            ->with($this->equalTo('my-service'))
            ->willReturn($mockService);

        $handler = $this->factory->__invoke($options);

        $this->assertInstanceOf(GelfHandler::class, $handler);
    }
}
