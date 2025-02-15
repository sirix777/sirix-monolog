<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Handler;

use Monolog\Handler\FingersCrossed\ActivationStrategyInterface;
use Monolog\Handler\FingersCrossedHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Level;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Sirix\Monolog\Handler\FingersCrossedHandlerFactory;
use Sirix\Monolog\Service\HandlerManager;

class FingersCrossedHandlerFactoryTest extends TestCase
{
    private FingersCrossedHandlerFactory $factory;
    private object $mockContainer;
    private object $mockHandlerManager;

    // @phpcs:ignore

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        $this->factory = new FingersCrossedHandlerFactory();

        $this->mockContainer = $this->createMock(ContainerInterface::class);
        $this->factory->setContainer($this->mockContainer);

        $this->mockHandlerManager = $this->getMockBuilder(HandlerManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->factory->setHandlerManager($this->mockHandlerManager);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testInvoke()
    {
        $options = [
            'handler' => 'my-handler',
            'activationStrategy' => 'my-strategy',
            'bufferSize' => 0,
            'bubble' => false,
            'stopBuffering' => true,
            'passthruLevel' => Level::Critical,
        ];

        $mockService = $this->createMock(ActivationStrategyInterface::class);

        $this->mockContainer->expects($this->once())
            ->method('has')
            ->with($this->equalTo('my-strategy'))
            ->willReturn(true)
        ;

        $this->mockContainer->expects($this->once())
            ->method('get')
            ->with($this->equalTo('my-strategy'))
            ->willReturn($mockService)
        ;

        $mockHandler = $this->createMock(HandlerInterface::class);

        $this->mockHandlerManager->expects($this->once())
            ->method('get')
            ->with($this->equalTo('my-handler'))
            ->willReturn($mockHandler)
        ;

        $handler = $this->factory->__invoke($options);

        $this->assertInstanceOf(FingersCrossedHandler::class, $handler);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testGetActivationWithNull()
    {
        $options = [
            'handler' => 'my-handler',
            'activationStrategy' => null,
            'bufferSize' => 0,
            'bubble' => false,
            'stopBuffering' => true,
            'passthruLevel' => Level::Critical,
        ];

        $this->mockContainer->expects($this->never())
            ->method('has')
        ;

        $this->mockContainer->expects($this->never())
            ->method('get')
        ;

        $mockHandler = $this->createMock(HandlerInterface::class);

        $this->mockHandlerManager->expects($this->once())
            ->method('get')
            ->with($this->equalTo('my-handler'))
            ->willReturn($mockHandler)
        ;

        $handler = $this->factory->__invoke($options);

        $this->assertInstanceOf(FingersCrossedHandler::class, $handler);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testGetActivationWithErrorLevel()
    {
        $options = [
            'handler' => 'my-handler',
            'activationStrategy' => Level::Critical,
            'bufferSize' => 0,
            'bubble' => false,
            'stopBuffering' => true,
            'passthruLevel' => Level::Critical,
        ];

        $this->mockContainer->expects($this->never())
            ->method('get')
        ;

        $mockHandler = $this->createMock(HandlerInterface::class);

        $this->mockHandlerManager->expects($this->once())
            ->method('get')
            ->with($this->equalTo('my-handler'))
            ->willReturn($mockHandler)
        ;

        $handler = $this->factory->__invoke($options);

        $this->assertInstanceOf(FingersCrossedHandler::class, $handler);
    }
}
