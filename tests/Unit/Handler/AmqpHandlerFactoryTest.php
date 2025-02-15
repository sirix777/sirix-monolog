<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Handler;

use Sirix\Monolog\Handler\AmqpHandlerFactory;
use Codeception\Test\Unit;
use Monolog\Handler\AmqpHandler;
use Monolog\Level;
use PhpAmqpLib\Channel\AMQPChannel;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class AmqpHandlerFactoryTest extends Unit
{
    private AmqpHandlerFactory $factory;
    private object $mockContainer;

    // @phpcs:ignore
    public function _before(): void
    {
        parent::_before();
        $this->factory = new AmqpHandlerFactory();
        $this->mockContainer = $this->createMock(ContainerInterface::class);
        $this->factory->setContainer($this->mockContainer);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testInvoke()
    {
        $options = [
            'exchange' => 'my-service',
            'exchangeName' => 'logger',
            'level' => Level::Info,
            'bubble' => false,
        ];

        $mockService = $this->createMock(AMQPChannel::class);

        $this->mockContainer->expects($this->once())
            ->method('has')
            ->with($this->equalTo('my-service'))
            ->willReturn(true);

        $this->mockContainer->expects($this->once())
            ->method('get')
            ->with($this->equalTo('my-service'))
            ->willReturn($mockService);

        $handler = $this->factory->__invoke($options);

        $this->assertInstanceOf(AmqpHandler::class, $handler);
    }
}
