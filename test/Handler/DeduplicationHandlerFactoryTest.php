<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Handler;

use Monolog\Handler\DeduplicationHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Level;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Handler\DeduplicationHandlerFactory;
use Sirix\Monolog\Service\HandlerManager;

class DeduplicationHandlerFactoryTest extends TestCase
{
    private DeduplicationHandlerFactory $factory;
    private object $mockHandlerManager;

    public function setUp(): void
    {
        $this->factory = new DeduplicationHandlerFactory();

        $this->mockHandlerManager = $this->getMockBuilder(HandlerManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->factory->setHandlerManager($this->mockHandlerManager);
    }

    public function testInvoke()
    {
        $options = [
            'handler' => 'my-handler',
            'deduplicationStore' => '/tmp/store',
            'deduplicationLevel' => Level::Debug,
            'time' => 2,
        ];

        $mockHandler = $this->createMock(HandlerInterface::class);

        $this->mockHandlerManager->expects($this->once())
            ->method('get')
            ->with($this->equalTo('my-handler'))
            ->willReturn($mockHandler)
        ;

        $handler = $this->factory->__invoke($options);

        $this->assertInstanceOf(DeduplicationHandler::class, $handler);
    }
}
