<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Handler;

use Gelf\PublisherInterface;
use Monolog\Handler\GelfHandler;
use Monolog\Level;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Sirix\Monolog\Handler\GelfHandlerFactory;

class GroupHandlerFactoryTest extends TestCase
{
    private GelfHandlerFactory $factory;
    private object $mockContainer;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
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
            ->willReturn(true)
        ;

        $this->mockContainer->expects($this->once())
            ->method('get')
            ->with($this->equalTo('my-service'))
            ->willReturn($mockService)
        ;

        $handler = $this->factory->__invoke($options);

        $this->assertInstanceOf(GelfHandler::class, $handler);
    }
}
