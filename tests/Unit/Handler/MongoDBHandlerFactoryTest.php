<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Handler;

use Sirix\Monolog\Handler\MongoDBHandlerFactory;
use Codeception\Test\Unit;
use MongoDB\Client;
use MongoDB\Collection;
use Monolog\Handler\MongoDBHandler;
use Monolog\Level;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class MongoDBHandlerFactoryTest extends Unit
{
    private MongoDBHandlerFactory $factory;
    private object $mockContainer;

    // @phpcs:ignore
    public function _before(): void
    {
        parent::_before();
        $this->factory = new MongoDBHandlerFactory();
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
            'client' => 'my-service',
            'database' => 'logger',
            'collection' => 'logger',
            'level' => Level::Info,
            'bubble' => false,
        ];

        $mockService = $this->createMock(Client::class);
        $collection = $this->createMock(Collection::class);

        $mockService->method('selectCollection')->willReturn($collection);

        $this->mockContainer->expects($this->once())
            ->method('has')
            ->with($this->equalTo('my-service'))
            ->willReturn(true);

        $this->mockContainer->expects($this->once())
            ->method('get')
            ->with($this->equalTo('my-service'))
            ->willReturn($mockService);

        $handler = $this->factory->__invoke($options);

        $this->assertInstanceOf(MongoDBHandler::class, $handler);
    }
}
