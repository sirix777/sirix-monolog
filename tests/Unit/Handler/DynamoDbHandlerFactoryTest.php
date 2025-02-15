<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Handler;

use Aws\DynamoDb\DynamoDbClient;
use Sirix\Monolog\Handler\DynamoDbHandlerFactory;
use Codeception\Test\Unit;
use Monolog\Handler\DynamoDbHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;

class DynamoDbHandlerFactoryTest extends Unit
{
    private DynamoDbHandlerFactory $factory;
    private object $mockContainer;

    // @phpcs:ignore
    public function _before(): void
    {
        parent::_before();
        $this->factory = new DynamoDbHandlerFactory();
        $this->mockContainer = $this->createMock(ContainerInterface::class);
        $this->factory->setContainer($this->mockContainer);
    }

    public function testInvoke()
    {
        $options = [
            'client' => 'my-service',
            'table' => 'monolog',
            'level' => Level::Info,
            'bubble' => false,
        ];

        $mockService = $this->createMock(DynamoDbClient::class);

        $this->mockContainer->expects($this->once())
            ->method('has')
            ->with($this->equalTo('my-service'))
            ->willReturn(true);

        $this->mockContainer->expects($this->once())
            ->method('get')
            ->with($this->equalTo('my-service'))
            ->willReturn($mockService);

        $handler = $this->factory->__invoke($options);

        $this->assertInstanceOf(DynamoDbHandler::class, $handler);
    }
}
