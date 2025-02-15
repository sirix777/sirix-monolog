<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Handler;

use Elastica\Client;
use Monolog\Handler\ElasticaHandler;
use Monolog\Level;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Sirix\Monolog\Handler\ElasticaHandlerFactory;

class ElasticaHandlerFactoryTest extends TestCase
{
    private ElasticaHandlerFactory $factory;
    private object $mockContainer;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        $this->factory = new ElasticaHandlerFactory();
        $this->mockContainer = $this->createMock(ContainerInterface::class);
        $this->factory->setContainer($this->mockContainer);
    }

    public function testInvoke()
    {
        $options = [
            'client' => 'my-service',
            'index' => 'monolog',
            'type' => 'record',
            'ignoreError' => false,
            'level' => Level::Info,
            'bubble' => false,
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

        $this->assertInstanceOf(ElasticaHandler::class, $handler);
    }
}
