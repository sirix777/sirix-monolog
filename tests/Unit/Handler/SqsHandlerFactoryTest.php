<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Handler;

use Aws\Sqs\SqsClient;
use Sirix\Monolog\Handler\SqsHandlerFactory;
use Codeception\Test\Unit;
use Monolog\Handler\SqsHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;

class SqsHandlerFactoryTest extends Unit
{
    private SqsHandlerFactory $factory;
    private object $mockContainer;

    // @phpcs:ignore
    public function _before(): void
    {
        parent::_before();
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
            ->willReturn(true);

        $this->mockContainer->expects($this->once())
            ->method('get')
            ->with($this->equalTo('my-service'))
            ->willReturn($mockService);

        $handler = $this->factory->__invoke($options);

        $this->assertInstanceOf(SqsHandler::class, $handler);
    }
}
