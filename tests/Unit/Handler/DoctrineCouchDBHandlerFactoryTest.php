<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Handler;

use Sirix\Monolog\Handler\DoctrineCouchDBHandlerFactory;
use Codeception\Test\Unit;
use Doctrine\CouchDB\CouchDBClient;
use Monolog\Handler\DoctrineCouchDBHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;

class DoctrineCouchDBHandlerFactoryTest extends Unit
{
    private DoctrineCouchDBHandlerFactory $factory;
    private object $mockContainer;

    // @phpcs:ignore
    public function _before(): void
    {
        parent::_before();
        $this->factory = new DoctrineCouchDBHandlerFactory();
        $this->mockContainer = $this->createMock(ContainerInterface::class);
        $this->factory->setContainer($this->mockContainer);
    }

    public function testInvoke()
    {
        $options = [
            'client' => 'my-service',
            'level' => Level::Info,
            'bubble' => false,
        ];

        $mockService = $this->createMock(CouchDBClient::class);

        $this->mockContainer->expects($this->once())
            ->method('has')
            ->with($this->equalTo('my-service'))
            ->willReturn(true);

        $this->mockContainer->expects($this->once())
            ->method('get')
            ->with($this->equalTo('my-service'))
            ->willReturn($mockService);

        $handler = $this->factory->__invoke($options);

        $this->assertInstanceOf(DoctrineCouchDBHandler::class, $handler);
    }
}
