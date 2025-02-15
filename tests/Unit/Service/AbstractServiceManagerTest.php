<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Service;

use Sirix\Monolog\Config\HandlerConfig;
use Sirix\Monolog\Config\MainConfig;
use Sirix\Monolog\Config\ProcessorConfig;
use Sirix\Monolog\Exception\InvalidConfigException;
use Sirix\Monolog\Exception\UnknownServiceException;
use Sirix\Monolog\MapperInterface;
use Sirix\Monolog\Service\AbstractServiceManager;
use Sirix\Test\Monolog\Unit\Stub\FactoryStub;
use Sirix\Test\Monolog\Unit\Stub\HandlerStub;
use Sirix\Test\Monolog\Unit\Stub\ServiceManagerStub;
use Codeception\Test\Unit;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class AbstractServiceManagerTest extends Unit
{
    protected ProcessorConfig $config;
    private MockObject $container;
    private HandlerConfig|MockObject $mockHandlerConfig;
    private MapperInterface|MockObject $mockMapper;
    private ServiceManagerStub $service;

    // @phpcs:ignore
    public function _before(): void
    {
        parent::_before();
        $this->container = $this->createMock(ContainerInterface::class);

        $mockConfig = $this->getMockBuilder(MainConfig::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockHandlerConfig = $this->getMockBuilder(HandlerConfig::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockMapper = $this->createMock(MapperInterface::class);

        $this->service = new ServiceManagerStub(
            $mockConfig,
            $this->mockMapper,
            $this->container
        );

        $this->assertInstanceOf(AbstractServiceManager::class, $this->service);
    }

    public function testHasServiceFromContainer()
    {
        $this->container->expects($this->once())
            ->method('has')
            ->with('my-service')
            ->willReturn(true);

        $result = $this->service->has('my-service');
        $this->assertTrue($result);
    }

    public function testHasServiceFromConfig()
    {
        $this->container->expects($this->once())
            ->method('has')
            ->with('my-service')
            ->willReturn(false);

        $this->service->setHasServiceConfig(true);

        $result = $this->service->has('my-service');
        $this->assertTrue($result);
    }

    public function testGetServiceFromContainer()
    {
        $expected = $this->getMockBuilder(HandlerStub::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->container->expects($this->exactly(2))
            ->method('has')
            ->with('my-service')
            ->willReturn(true);

        $this->container->expects($this->exactly(1))
            ->method('get')
            ->with('my-service')
            ->willReturn($expected);

        $this->mockHandlerConfig->expects($this->never())
            ->method('getType');

        $this->mockHandlerConfig->expects($this->never())
            ->method('getOptions');

        $this->mockMapper->expects($this->never())
            ->method('map');

        $result = $this->service->get('my-service');
        $this->assertEquals($expected, $result);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testGetServiceFromFactoryClass()
    {
        $this->container->expects($this->exactly(2))
            ->method('has')
            ->with(FactoryStub::class)
            ->willReturn(false);

        $this->mockHandlerConfig->expects($this->once())
            ->method('getType')
            ->willReturn(FactoryStub::class);

        $this->mockHandlerConfig->expects($this->once())
            ->method('getOptions')
            ->willReturn([]);

        $this->mockMapper->expects($this->never())
            ->method('map');

        $this->service->setServiceConfig($this->mockHandlerConfig);
        $this->service->setHasServiceConfig(true);

        $result = $this->service->get(FactoryStub::class);
        $this->assertInstanceOf(HandlerStub::class, $result);
    }

    public function testGetServiceFromMapper()
    {
        $this->container->expects($this->exactly(2))
            ->method('has')
            ->with('my-service')
            ->willReturn(false);

        $this->mockHandlerConfig->expects($this->once())
            ->method('getType')
            ->willReturn('my-service');

        $this->mockHandlerConfig->expects($this->once())
            ->method('getOptions')
            ->willReturn([]);

        $this->mockMapper->expects($this->once())
            ->method('map')
            ->with('my-service')
            ->willReturn(FactoryStub::class);

        $this->service->setServiceConfig($this->mockHandlerConfig);
        $this->service->setHasServiceConfig(true);

        $result = $this->service->get('my-service');
        $this->assertInstanceOf(HandlerStub::class, $result);
    }

    public function testGetPreviouslyConstructedService()
    {
        $expected = $this->getMockBuilder(HandlerStub::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->container->expects($this->exactly(2))
            ->method('has')
            ->with('my-service')
            ->willReturn(true);

        $this->container->expects($this->exactly(1))
            ->method('get')
            ->with('my-service')
            ->willReturn($expected);

        $this->mockHandlerConfig->expects($this->never())
            ->method('getType');

        $this->mockHandlerConfig->expects($this->never())
            ->method('getOptions');

        $this->mockMapper->expects($this->never())
            ->method('map');

        $result = $this->service->get('my-service');
        $this->assertEquals($expected, $result);

        // No additional dependency calls should be made now
        $result = $this->service->has('my-service');
        $this->assertTrue($result);

        $result = $this->service->get('my-service');
        $this->assertEquals($expected, $result);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testGetServiceNotFound()
    {
        $this->expectException(UnknownServiceException::class);

        $this->container->expects($this->exactly(1))
            ->method('has')
            ->with('my-service')
            ->willReturn(false);

        $this->mockHandlerConfig->expects($this->never())
            ->method('getType');

        $this->mockHandlerConfig->expects($this->never())
            ->method('getOptions');

        $this->mockMapper->expects($this->never())
            ->method('map');

        $this->service->setServiceConfig($this->mockHandlerConfig);
        $this->service->setHasServiceConfig(false);

        $this->service->get('my-service');
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testGetServiceInvalidFactory()
    {
        $this->expectException(InvalidConfigException::class);

        $this->container->expects($this->exactly(2))
            ->method('has')
            ->with('my-service')
            ->willReturn(false);

        $this->mockHandlerConfig->expects($this->once())
            ->method('getType')
            ->willReturn('my-service');

        $this->mockHandlerConfig->expects($this->once())
            ->method('getOptions')
            ->willReturn([]);

        $this->mockMapper->expects($this->once())
            ->method('map')
            ->with('my-service')
            ->willReturn(null);

        $this->service->setServiceConfig($this->mockHandlerConfig);
        $this->service->setHasServiceConfig(true);

        $this->service->get('my-service');
    }
}
