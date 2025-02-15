<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Processor;

use ArrayAccess;
use Sirix\Monolog\Exception\MissingServiceException;
use Sirix\Monolog\Processor\WebProcessorFactory;
use Codeception\Test\Unit;
use Monolog\Processor\WebProcessor;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class WebProcessorFactoryTest extends Unit
{
    private WebProcessorFactory $factory;
    private MockObject|ContainerInterface $mockContainer;

    // @phpcs:ignore
    public function _before(): void
    {
        parent::_before();
        $this->factory = new WebProcessorFactory();
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
            'serverData' => [],
            'extraFields' => [],
        ];

        $handler = $this->factory->__invoke($options);
        $this->assertInstanceOf(WebProcessor::class, $handler);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testGetServerDataService()
    {
        $options = [
            'serverData' => 'my-service',
        ];

        $mockService = $this->createMock(ArrayAccess::class);

        $this->mockContainer->expects($this->once())
            ->method('has')
            ->with($this->equalTo('my-service'))
            ->willReturn(true);

        $this->mockContainer->expects($this->once())
            ->method('get')
            ->with($this->equalTo('my-service'))
            ->willReturn($mockService);

        $service = $this->factory->getServerDataService($options);
        $this->assertEquals($mockService, $service);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testGetServerDataWithArray()
    {
        $options = [
            'serverData' => ['someKey' => 'someVar'],
        ];

        $this->mockContainer->expects($this->never())
            ->method('has');

        $this->mockContainer->expects($this->never())
            ->method('get');

        $service = $this->factory->getServerDataService($options);
        $this->assertEquals($options['serverData'], $service);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testGetServerDataWithArrayObject()
    {
        $mockService = $this->createMock(ArrayAccess::class);

        $options = [
            'serverData' => $mockService,
        ];

        $this->mockContainer->expects($this->never())
            ->method('has');

        $this->mockContainer->expects($this->never())
            ->method('get');

        $service = $this->factory->getServerDataService($options);
        $this->assertEquals($options['serverData'], $service);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testGetAmqpExchangeMissingConfig()
    {
        $options = [];

        $this->mockContainer->expects($this->never())
            ->method('has');

        $this->mockContainer->expects($this->never())
            ->method('get');

        $result = $this->factory->getServerDataService($options);

        $this->assertEmpty($result);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testGetAmqpExchangeMissingService()
    {
        $this->expectException(MissingServiceException::class);

        $options = [
            'serverData' => 'my-service',
        ];

        $this->mockContainer->expects($this->once())
            ->method('has')
            ->with($this->equalTo('my-service'))
            ->willReturn(false);

        $this->mockContainer->expects($this->never())
            ->method('get');

        $this->factory->getServerDataService($options);
    }
}
