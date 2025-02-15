<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Service;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Sirix\Monolog\Config\FormatterConfig;
use Sirix\Monolog\Config\MainConfig;
use Sirix\Monolog\Config\ProcessorConfig;
use Sirix\Monolog\MapperInterface;
use Sirix\Monolog\Service\FormatterManager;

class FormatterManagerTest extends TestCase
{
    protected ProcessorConfig $config;
    private FormatterManager $service;
    private MainConfig|MockObject $mockConfig;
    private FormatterConfig $mockServiceConfig;

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

        $this->mockServiceConfig = $this->createMock(FormatterConfig::class);

        $mockMapper = $this->createMock(MapperInterface::class);

        $this->service = new FormatterManager(
            $this->mockConfig,
            $mockMapper,
            $mockContainer
        );

        $this->assertInstanceOf(FormatterManager::class, $this->service);
    }

    public function testGetServiceConfig()
    {
        $this->mockConfig->expects($this->once())
            ->method('getFormatterConfig')
            ->with('my-config-name')
            ->willReturn($this->mockServiceConfig)
        ;

        $result = $this->service->getServiceConfig('my-config-name');
        $this->assertEquals($this->mockServiceConfig, $result);
    }

    public function testHasServiceConfig()
    {
        $this->mockConfig->expects($this->once())
            ->method('hasFormatterConfig')
            ->with('my-config-name')
            ->willReturn(true)
        ;

        $result = $this->service->hasServiceConfig('my-config-name');
        $this->assertTrue($result);
    }
}
