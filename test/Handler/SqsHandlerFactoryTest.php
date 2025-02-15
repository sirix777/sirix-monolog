<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Handler;

use Aws\Sqs\SqsClient;
use Monolog\Handler\SqsHandler;
use Monolog\Level;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Sirix\Monolog\Handler\SqsHandlerFactory;

class SqsHandlerFactoryTest extends TestCase
{
    private SqsHandlerFactory $factory;
    private object $mockContainer;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        $this->factory = new SqsHandlerFactory();
        $this->mockContainer = $this->createMock(ContainerInterface::class);
        $this->factory->setContainer($this->mockContainer);
    }

    public function testInvoke()
    {
        $options = [
            'sqsClient' => 'my-service',
            'queueUrl' => 'logger',
            'level' => Level::Info,
            'bubble' => false,
        ];

        $mockService = $this->createMock(SqsClient::class);

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

        $this->assertInstanceOf(SqsHandler::class, $handler);
    }
}
