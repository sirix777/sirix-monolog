<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Handler;

use Monolog\Handler\RedisHandler;
use Monolog\Level;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Predis\Client;
use Psr\Container\ContainerInterface;
use Sirix\Monolog\Handler\RedisHandlerFactory;

class RedisHandlerFactoryTest extends TestCase
{
    private RedisHandlerFactory $factory;
    private object $mockContainer;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
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
            ->willReturn(true)
        ;

        $this->mockContainer->expects($this->once())
            ->method('get')
            ->with($this->equalTo('my-service'))
            ->willReturn($mockService)
        ;

        $handler = $this->factory->__invoke($options);

        $this->assertInstanceOf(RedisHandler::class, $handler);
    }
}
