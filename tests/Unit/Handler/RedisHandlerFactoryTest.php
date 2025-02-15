<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Handler;

use Sirix\Monolog\Handler\RedisHandlerFactory;
use Codeception\Test\Unit;
use Monolog\Handler\RedisHandler;
use Monolog\Level;
use Predis\Client;
use Psr\Container\ContainerInterface;

class RedisHandlerFactoryTest extends Unit
{
    private RedisHandlerFactory $factory;
    private object $mockContainer;

    // @phpcs:ignore
    public function _before(): void
    {
        parent::_before();
        $this->factory = new RedisHandlerFactory();
        $this->mockContainer = $this->createMock(ContainerInterface::class);
        $this->factory->setContainer($this->mockContainer);
    }

    public function testInvoke()
    {
        $options = [
            'client' => 'my-service',
            'key' => 'logger',
            'level' => Level::Info,
            'bubble' => false,
            'capSize' => 0,
        ];

        $mockService = $this->createMock(Client::class);

        $this->mockContainer->expects($this->once())
            ->method('has')
            ->with($this->equalTo('my-service'))
            ->willReturn(true);

        $this->mockContainer->expects($this->once())
            ->method('get')
            ->with($this->equalTo('my-service'))
            ->willReturn($mockService);

        $handler = $this->factory->__invoke($options);

        $this->assertInstanceOf(RedisHandler::class, $handler);
    }
}
