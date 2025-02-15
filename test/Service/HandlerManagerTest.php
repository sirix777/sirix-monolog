<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Service;

use Monolog\Formatter\LineFormatter;
use Monolog\Processor\GitProcessor;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Sirix\Monolog\Config\HandlerConfig;
use Sirix\Monolog\Config\MainConfig;
use Sirix\Monolog\Config\ProcessorConfig;
use Sirix\Monolog\Exception\MissingServiceException;
use Sirix\Monolog\Exception\UnknownServiceException;
use Sirix\Monolog\MapperInterface;
use Sirix\Monolog\Service\FormatterManager;
use Sirix\Monolog\Service\HandlerManager;
use Sirix\Monolog\Service\ProcessorManager;
use Sirix\Test\Monolog\Stub\HandlerStub;

class HandlerManagerTest extends TestCase
{
    protected ProcessorConfig $config;
    private HandlerManager $service;
    private MainConfig|MockObject $mockConfig;
    private HandlerConfig|MockObject $mockServiceConfig;
    private ContainerInterface|MockObject $mockContainer;
    private FormatterManager|MockObject $mockFormatterManager;
    private MockObject|ProcessorManager $mockProcessorManager;

    // @phpcs:ignore

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        $this->mockContainer = $this->createMock(ContainerInterface::class);

        $this->mockConfig = $this->getMockBuilder(MainConfig::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->mockServiceConfig = $this->getMockBuilder(HandlerConfig::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $mockMapper = $this->createMock(MapperInterface::class);

        $this->mockFormatterManager = $this->getMockBuilder(FormatterManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->mockProcessorManager = $this->getMockBuilder(ProcessorManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->service = new HandlerManager(
            $this->mockConfig,
            $mockMapper,
            $this->mockContainer
        );

        $this->assertInstanceOf(HandlerManager::class, $this->service);
    }

    public function testGetAndSetFormatterManager()
    {
        $this->service->setFormatterManager($this->mockFormatterManager);
        $result = $this->service->getFormatterManager();
        $this->assertEquals($this->mockFormatterManager, $result);
    }

    public function testGetAndSetProcessorManager()
    {
        $this->service->setProcessorManager($this->mockProcessorManager);
        $result = $this->service->getProcessorManager();
        $this->assertEquals($this->mockProcessorManager, $result);
    }

    public function testGetServiceConfig()
    {
        $this->mockConfig->expects($this->exactly(1))
            ->method('getHandlerConfig')
            ->with('my-config-name')
            ->willReturn($this->mockServiceConfig)
        ;

        $result = $this->service->getServiceConfig('my-config-name');
        $this->assertEquals($this->mockServiceConfig, $result);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testGetFromParentOnly()
    {
        $expected = $this->getMockBuilder(HandlerStub::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->service->setFormatterManager($this->mockFormatterManager);

        $this->mockContainer->expects($this->exactly(2))
            ->method('has')
            ->with('my-service')
            ->willReturn(true)
        ;

        $this->mockContainer->expects($this->exactly(1))
            ->method('get')
            ->with('my-service')
            ->willReturn($expected)
        ;

        $this->mockConfig->expects($this->never())
            ->method('hasHandlerConfig')
        ;

        $this->mockServiceConfig->expects($this->never())
            ->method('getFormatter')
            ->willReturn('')
        ;

        $result = $this->service->get('my-service');
        $this->assertEquals($expected, $result);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testGetWithAdditionalFormatter()
    {
        $expected = $this->getMockBuilder(HandlerStub::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->service->setFormatterManager($this->mockFormatterManager);

        $this->mockContainer->expects($this->exactly(2))
            ->method('has')
            ->with('my-service')
            ->willReturn(true)
        ;

        $this->mockContainer->expects($this->exactly(1))
            ->method('get')
            ->with('my-service')
            ->willReturn($expected)
        ;

        $this->mockConfig->expects($this->once())
            ->method('getHandlerConfig')
            ->with('my-service')
            ->willReturn($this->mockServiceConfig)
        ;

        $mockFormatter = $this->getMockBuilder(LineFormatter::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->mockServiceConfig->expects($this->once())
            ->method('getFormatter')
            ->willReturn('my-formatter')
        ;

        $this->mockServiceConfig->expects($this->once())
            ->method('getProcessors')
            ->willReturn([])
        ;

        $expected->expects($this->once())
            ->method('setFormatter')
            ->with($mockFormatter)
            ->willReturn($expected)
        ;

        $this->mockFormatterManager->expects($this->once())
            ->method('has')
            ->with('my-formatter')
            ->willReturn(true)
        ;

        $this->mockFormatterManager->expects($this->once())
            ->method('get')
            ->with('my-formatter')
            ->willReturn($mockFormatter)
        ;

        $result = $this->service->get('my-service');
        $this->assertEquals($expected, $result);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testGetFromPreviouslyConstructedService()
    {
        $expected = $this->getMockBuilder(HandlerStub::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->service->setFormatterManager($this->mockFormatterManager);

        $this->mockContainer->expects($this->exactly(2))
            ->method('has')
            ->with('my-service')
            ->willReturn(true)
        ;

        $this->mockContainer->expects($this->exactly(1))
            ->method('get')
            ->with('my-service')
            ->willReturn($expected)
        ;

        $this->mockConfig->expects($this->never())
            ->method('hasHandlerConfig')
        ;

        $this->mockServiceConfig->expects($this->never())
            ->method('getFormatter')
            ->willReturn('')
        ;

        $result = $this->service->get('my-service');
        $this->assertEquals($expected, $result);

        // No additional dependency calls should happen here
        $result = $this->service->get('my-service');
        $this->assertEquals($expected, $result);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testGetMissingFormatterManager()
    {
        $this->expectException(MissingServiceException::class);

        $expected = $this->getMockBuilder(HandlerStub::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->mockContainer->expects($this->exactly(2))
            ->method('has')
            ->with('my-service')
            ->willReturn(true)
        ;

        $this->mockContainer->expects($this->exactly(1))
            ->method('get')
            ->with('my-service')
            ->willReturn($expected)
        ;

        $this->mockConfig->expects($this->once())
            ->method('getHandlerConfig')
            ->with('my-service')
            ->willReturn($this->mockServiceConfig)
        ;

        $this->mockServiceConfig->expects($this->once())
            ->method('getFormatter')
            ->willReturn('my-formatter')
        ;

        $expected->expects($this->never())
            ->method('setFormatter')
        ;

        $this->mockFormatterManager->expects($this->never())
            ->method('has')
        ;

        $this->mockFormatterManager->expects($this->never())
            ->method('get')
        ;

        $result = $this->service->get('my-service');
        $this->assertEquals($expected, $result);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testGetMissingFormatterService()
    {
        $this->expectException(UnknownServiceException::class);

        $expected = $this->getMockBuilder(HandlerStub::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->service->setFormatterManager($this->mockFormatterManager);

        $this->mockContainer->expects($this->exactly(2))
            ->method('has')
            ->with('my-service')
            ->willReturn(true)
        ;

        $this->mockContainer->expects($this->exactly(1))
            ->method('get')
            ->with('my-service')
            ->willReturn($expected)
        ;

        $this->mockConfig->expects($this->once())
            ->method('getHandlerConfig')
            ->with('my-service')
            ->willReturn($this->mockServiceConfig)
        ;

        $this->mockServiceConfig->expects($this->once())
            ->method('getFormatter')
            ->willReturn('my-formatter')
        ;

        $expected->expects($this->never())
            ->method('setFormatter')
        ;

        $this->mockFormatterManager->expects($this->once())
            ->method('has')
            ->with('my-formatter')
            ->willReturn(false)
        ;

        $this->mockFormatterManager->expects($this->never())
            ->method('get')
        ;

        $this->service->get('my-service');
    }

    public function testHasServiceConfig()
    {
        $this->mockConfig->expects($this->once())
            ->method('hasHandlerConfig')
            ->with('my-config-name')
            ->willReturn(true)
        ;

        $result = $this->service->hasServiceConfig('my-config-name');
        $this->assertTrue($result);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testProcessorIsPushed()
    {
        $expected = $this->getMockBuilder(HandlerStub::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->service->setFormatterManager($this->mockFormatterManager);
        $this->service->setProcessorManager($this->mockProcessorManager);

        $this->mockContainer->expects($this->exactly(2))
            ->method('has')
            ->with('my-service')
            ->willReturn(true)
        ;

        $this->mockContainer->expects($this->exactly(1))
            ->method('get')
            ->with('my-service')
            ->willReturn($expected)
        ;

        $this->mockConfig->expects($this->once())
            ->method('getHandlerConfig')
            ->with('my-service')
            ->willReturn($this->mockServiceConfig)
        ;

        $mockProcessor = $this->getMockBuilder(GitProcessor::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->mockProcessorManager->expects($this->once())
            ->method('get')
            ->with('my-processor')
            ->willReturn($mockProcessor)
        ;

        $this->mockServiceConfig->expects($this->once())
            ->method('getProcessors')
            ->willReturn(['my-processor'])
        ;

        $expected->expects($this->once())
            ->method('pushProcessor')
            ->with($mockProcessor)
            ->willReturn($expected)
        ;

        // No additional dependency calls should happen here
        $result = $this->service->get('my-service');
        $this->assertEquals($expected, $result);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testGetMissingProcessorManager()
    {
        $this->expectException(MissingServiceException::class);

        $expected = $this->getMockBuilder(HandlerStub::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->mockContainer->expects($this->exactly(2))
            ->method('has')
            ->with('my-service')
            ->willReturn(true)
        ;

        $this->mockContainer->expects($this->exactly(1))
            ->method('get')
            ->with('my-service')
            ->willReturn($expected)
        ;

        $this->mockConfig->expects($this->once())
            ->method('getHandlerConfig')
            ->with('my-service')
            ->willReturn($this->mockServiceConfig)
        ;

        $this->mockServiceConfig->expects($this->once())
            ->method('getFormatter')
            ->willReturn('');

        $expected->expects($this->never())
            ->method('setFormatter');

        $this->mockServiceConfig->expects($this->once())
            ->method('getProcessors')
            ->willReturn(['my-processor']);

        $expected->expects($this->never())
            ->method('pushProcessor');

        $result = $this->service->get('my-service');
        $this->assertEquals($expected, $result);
    }
}
