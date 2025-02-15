<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Handler;

use Aws\DynamoDb\DynamoDbClient;
use Monolog\Handler\DynamoDbHandler;
use Monolog\Level;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Sirix\Monolog\Handler\DynamoDbHandlerFactory;

class DynamoDbHandlerFactoryTest extends TestCase
{
    private DynamoDbHandlerFactory $factory;
    private object $mockContainer;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
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
            ->willReturn(true)
        ;

        $this->mockContainer->expects($this->once())
            ->method('get')
            ->with($this->equalTo('my-service'))
            ->willReturn($mockService)
        ;

        $handler = $this->factory->__invoke($options);

        $this->assertInstanceOf(DynamoDbHandler::class, $handler);
    }
}
