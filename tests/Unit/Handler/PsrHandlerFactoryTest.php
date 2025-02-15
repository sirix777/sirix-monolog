<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Handler;

use Sirix\Monolog\Handler\PsrHandlerFactory;
use Codeception\Test\Unit;
use Monolog\Handler\PsrHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class PsrHandlerFactoryTest extends Unit
{
    private PsrHandlerFactory $factory;
    private object $mockContainer;

    // @phpcs:ignore
    public function _before(): void
    {
        parent::_before();
        $this->factory = new PsrHandlerFactory();
        $this->mockContainer = $this->createMock(ContainerInterface::class);
        $this->factory->setContainer($this->mockContainer);
    }

    public function testInvoke()
    {
        $options = [
            'logger' => 'my-service',
            'level' => Level::Info,
            'bubble' => false,
        ];

        $mockService = $this->createMock(LoggerInterface::class);

        $this->mockContainer->expects($this->once())
            ->method('has')
            ->with($this->equalTo('my-service'))
            ->willReturn(true);

        $this->mockContainer->expects($this->once())
            ->method('get')
            ->with($this->equalTo('my-service'))
            ->willReturn($mockService);

        $handler = $this->factory->__invoke($options);

        $this->assertInstanceOf(PsrHandler::class, $handler);
    }
}
