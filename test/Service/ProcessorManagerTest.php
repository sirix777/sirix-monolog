<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Service;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Sirix\Monolog\Config\HandlerConfig;
use Sirix\Monolog\Config\MainConfig;
use Sirix\Monolog\Config\ProcessorConfig;
use Sirix\Monolog\MapperInterface;
use Sirix\Monolog\Service\ProcessorManager;

class ProcessorManagerTest extends TestCase
{
    protected ProcessorConfig $config;
    private ProcessorManager $service;
    private MainConfig|MockObject $mockConfig;
    private HandlerConfig|MockObject $mockServiceConfig;

    // @phpcs:ignore

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        $mockContainer = $this->createMock(ContainerInterface::class);

        $this->mockConfig = $this->getMockBuilder(MainConfig::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->mockServiceConfig = $this->createMock(ProcessorConfig::class);

        $mockMapper = $this->createMock(MapperInterface::class);

        $this->service = new ProcessorManager(
            $this->mockConfig,
            $mockMapper,
            $mockContainer
        );

        $this->assertInstanceOf(ProcessorManager::class, $this->service);
    }

    public function testGetServiceConfig()
    {
        $this->mockConfig->expects($this->once())
            ->method('getProcessorConfig')
            ->with('my-config-name')
            ->willReturn($this->mockServiceConfig)
        ;

        $result = $this->service->getServiceConfig('my-config-name');
        $this->assertEquals($this->mockServiceConfig, $result);
    }

    public function testHasServiceConfig()
    {
        $this->mockConfig->expects($this->once())
            ->method('hasProcessorConfig')
            ->with('my-config-name')
            ->willReturn(true)
        ;

        $result = $this->service->hasServiceConfig('my-config-name');
        $this->assertTrue($result);
    }
}
