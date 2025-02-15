<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Handler;

use Monolog\Handler\FilterHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Level;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Handler\FilterHandlerFactory;
use Sirix\Monolog\Service\HandlerManager;

class FilterHandlerFactoryTest extends TestCase
{
    private FilterHandlerFactory $factory;
    private object $mockHandlerManager;

    // @phpcs:ignore
    public function setUp(): void
    {
        $this->factory = new FilterHandlerFactory();

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
            'minLevelOrList' => Level::Debug,
            'maxLevel' => Level::Debug,
            'bubble' => true,
        ];

        $mockHandler = $this->createMock(HandlerInterface::class);

        $this->mockHandlerManager->expects($this->once())
            ->method('get')
            ->with($this->equalTo('my-handler'))
            ->willReturn($mockHandler)
        ;

        $handler = $this->factory->__invoke($options);

        $this->assertInstanceOf(FilterHandler::class, $handler);
    }
}
