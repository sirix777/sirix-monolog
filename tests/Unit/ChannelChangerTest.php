<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit;

use Sirix\Monolog\ChannelChanger;
use Sirix\Monolog\Config\ChannelConfig;
use Sirix\Monolog\Config\MainConfig;
use Sirix\Monolog\Exception\MissingConfigException;
use Sirix\Monolog\Exception\UnknownServiceException;
use Sirix\Monolog\Service\HandlerManager;
use Sirix\Monolog\Service\ProcessorManager;
use Codeception\Test\Unit;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ChannelChangerTest extends Unit
{
    private MainConfig|MockObject $mockConfig;
    private MockObject|HandlerManager $mockHandlerManager;
    private MockObject|ProcessorManager $mockProcessorManager;
    private ChannelConfig|MockObject $mockChannelConfig;
    private StreamHandler|MockObject $mockHandler;
    private PsrLogMessageProcessor|MockObject $mockProcessor;
    private ChannelChanger $service;

    // @phpcs:ignore
    public function _before(): void
    {
        parent::_before();
        $this->mockConfig = $this->getMockBuilder(MainConfig::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockHandlerManager = $this->getMockBuilder(HandlerManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockProcessorManager = $this->getMockBuilder(ProcessorManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockChannelConfig = $this->getMockBuilder(ChannelConfig::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockHandler = $this->getMockBuilder(StreamHandler::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockProcessor = $this->getMockBuilder(PsrLogMessageProcessor::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->service = new ChannelChanger(
            $this->mockConfig,
            $this->mockHandlerManager,
            $this->mockProcessorManager
        );

        $this->assertInstanceOf(ChannelChanger::class, $this->service);
    }

    public function testHas()
    {
        $this->mockConfig->expects($this->once())
            ->method('hasChannelConfig')
            ->with('myChannel')
            ->willReturn(true);

        $result = $this->service->has('myChannel');
        $this->assertTrue($result);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testGet()
    {
        $this->mockConfig->expects($this->once())
            ->method('hasChannelConfig')
            ->with('myChannel')
            ->willReturn(true);

        $this->mockConfig->expects($this->once())
            ->method('getChannelConfig')
            ->with('myChannel')
            ->willReturn($this->mockChannelConfig);

        /* Handler */
        $this->mockChannelConfig->expects($this->once())
            ->method('getHandlers')
            ->willReturn(['myHandler']);

        $this->mockHandlerManager->expects($this->once())
            ->method('has')
            ->with('myHandler')
            ->willReturn(true);

        $this->mockHandlerManager->expects($this->once())
            ->method('get')
            ->with('myHandler')
            ->willReturn($this->mockHandler);

        /* Processor */
        $this->mockChannelConfig->expects($this->once())
            ->method('getProcessors')
            ->willReturn(['myProcessor']);

        $this->mockProcessorManager->expects($this->once())
            ->method('has')
            ->with('myProcessor')
            ->willReturn(true);

        $this->mockProcessorManager->expects($this->once())
            ->method('get')
            ->with('myProcessor')
            ->willReturn($this->mockProcessor);

        /* Name */
        $this->mockChannelConfig->expects($this->once())
            ->method('getName')
            ->willReturn(null);

        /** @var Logger $result */
        $result = $this->service->get('myChannel');
        $this->assertInstanceOf(Logger::class, $result);

        $handlers = $result->getHandlers();
        $this->assertEquals($this->mockHandler, $handlers[0]);

        $processors = $result->getProcessors();
        $this->assertEquals($this->mockProcessor, $processors[0]);

        $name = $result->getName();
        $this->assertEquals('myChannel', $name);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testGetFromCache()
    {
        $this->mockConfig->expects($this->once())
            ->method('hasChannelConfig')
            ->with('myChannel')
            ->willReturn(true);

        $this->mockConfig->expects($this->once())
            ->method('getChannelConfig')
            ->with('myChannel')
            ->willReturn($this->mockChannelConfig);

        /* Handler */
        $this->mockChannelConfig->expects($this->once())
            ->method('getHandlers')
            ->willReturn(['myHandler']);

        $this->mockHandlerManager->expects($this->once())
            ->method('has')
            ->with('myHandler')
            ->willReturn(true);

        $this->mockHandlerManager->expects($this->once())
            ->method('get')
            ->with('myHandler')
            ->willReturn($this->mockHandler);

        /* Processor */
        $this->mockChannelConfig->expects($this->once())
            ->method('getProcessors')
            ->willReturn(['myProcessor']);

        $this->mockProcessorManager->expects($this->once())
            ->method('has')
            ->with('myProcessor')
            ->willReturn(true);

        $this->mockProcessorManager->expects($this->once())
            ->method('get')
            ->with('myProcessor')
            ->willReturn($this->mockProcessor);

        /* Name */
        $this->mockChannelConfig->expects($this->once())
            ->method('getName')
            ->willReturn(null);

        /** @var Logger $result */
        $result = $this->service->get('myChannel');
        $this->assertInstanceOf(Logger::class, $result);

        /* Should not call mocks again */
        $result = $this->service->get('myChannel');
        $this->assertInstanceOf(Logger::class, $result);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testGetWithMissingChannelConfig()
    {
        $this->expectException(MissingConfigException::class);
        $this->mockConfig->expects($this->once())
            ->method('hasChannelConfig')
            ->with('myChannel')
            ->willReturn(false);

        $this->mockConfig->expects($this->never())
            ->method('getChannelConfig');

        /* Handler */
        $this->mockChannelConfig->expects($this->never())
            ->method('getHandlers');

        $this->mockHandlerManager->expects($this->never())
            ->method('has');

        $this->mockHandlerManager->expects($this->never())
            ->method('get');

        /* Processor */
        $this->mockChannelConfig->expects($this->never())
            ->method('getProcessors');

        $this->mockProcessorManager->expects($this->never())
            ->method('has');

        $this->mockProcessorManager->expects($this->never())
            ->method('get');

        /* Name */
        $this->mockChannelConfig->expects($this->never())
            ->method('getName');

        /** @var Logger $result */
        $result = $this->service->get('myChannel');
        $this->assertInstanceOf(Logger::class, $result);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testGetWithMissingHandler()
    {
        $this->expectException(UnknownServiceException::class);

        $this->mockConfig->expects($this->once())
            ->method('hasChannelConfig')
            ->with('myChannel')
            ->willReturn(true);

        $this->mockConfig->expects($this->once())
            ->method('getChannelConfig')
            ->with('myChannel')
            ->willReturn($this->mockChannelConfig);

        /* Handler */
        $this->mockChannelConfig->expects($this->once())
            ->method('getHandlers')
            ->willReturn(['myHandler']);

        $this->mockHandlerManager->expects($this->once())
            ->method('has')
            ->with('myHandler')
            ->willReturn(false);

        $this->mockHandlerManager->expects($this->never())
            ->method('get');

        /* Processor */
        $this->mockChannelConfig->expects($this->never())
            ->method('getProcessors');

        $this->mockProcessorManager->expects($this->never())
            ->method('has');

        $this->mockProcessorManager->expects($this->never())
            ->method('get');

        /* Name */
        $this->mockChannelConfig->expects($this->once())
            ->method('getName')
            ->willReturn(null);

        /** @var Logger $result */
        $result = $this->service->get('myChannel');
        $this->assertInstanceOf(Logger::class, $result);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testGetWithMissingProcessor()
    {
        $this->expectException(UnknownServiceException::class);

        $this->mockConfig->expects($this->once())
            ->method('hasChannelConfig')
            ->with('myChannel')
            ->willReturn(true);

        $this->mockConfig->expects($this->once())
            ->method('getChannelConfig')
            ->with('myChannel')
            ->willReturn($this->mockChannelConfig);

        /* Handler */
        $this->mockChannelConfig->expects($this->once())
            ->method('getHandlers')
            ->willReturn(['myHandler']);

        $this->mockHandlerManager->expects($this->once())
            ->method('has')
            ->with('myHandler')
            ->willReturn(true);

        $this->mockHandlerManager->expects($this->once())
            ->method('get')
            ->with('myHandler')
            ->willReturn($this->mockHandler);

        /* Processor */
        $this->mockChannelConfig->expects($this->once())
            ->method('getProcessors')
            ->willReturn(['myProcessor']);

        $this->mockProcessorManager->expects($this->once())
            ->method('has')
            ->with('myProcessor')
            ->willReturn(false);

        $this->mockProcessorManager->expects($this->never())
            ->method('get');

        /* Name */
        $this->mockChannelConfig->expects($this->once())
            ->method('getName')
            ->willReturn(null);

        /** @var Logger $result */
        $result = $this->service->get('myChannel');
        $this->assertInstanceOf(Logger::class, $result);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testGetWithCustomName()
    {
        $this->mockConfig->expects($this->once())
            ->method('hasChannelConfig')
            ->with('myChannel')
            ->willReturn(true);

        $this->mockConfig->expects($this->once())
            ->method('getChannelConfig')
            ->with('myChannel')
            ->willReturn($this->mockChannelConfig);

        /* Handler */
        $this->mockChannelConfig->expects($this->once())
            ->method('getHandlers')
            ->willReturn(['myHandler']);

        $this->mockHandlerManager->expects($this->once())
            ->method('has')
            ->with('myHandler')
            ->willReturn(true);

        $this->mockHandlerManager->expects($this->once())
            ->method('get')
            ->with('myHandler')
            ->willReturn($this->mockHandler);

        /* Processor */
        $this->mockChannelConfig->expects($this->once())
            ->method('getProcessors')
            ->willReturn(['myProcessor']);

        $this->mockProcessorManager->expects($this->once())
            ->method('has')
            ->with('myProcessor')
            ->willReturn(true);

        $this->mockProcessorManager->expects($this->once())
            ->method('get')
            ->with('myProcessor')
            ->willReturn($this->mockProcessor);

        /* Name */
        $this->mockChannelConfig->expects($this->once())
            ->method('getName')
            ->willReturn('customName');

        /** @var Logger $result */
        $result = $this->service->get('myChannel');
        $this->assertInstanceOf(Logger::class, $result);

        $name = $result->getName();

        $this->assertEquals('customName', $name);
    }
}
