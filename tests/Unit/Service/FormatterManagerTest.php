<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Service;

use Sirix\Monolog\Config\FormatterConfig;
use Sirix\Monolog\Config\MainConfig;
use Sirix\Monolog\Config\ProcessorConfig;
use Sirix\Monolog\MapperInterface;
use Sirix\Monolog\Service\FormatterManager;
use Codeception\Test\Unit;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Container\ContainerInterface;

class FormatterManagerTest extends Unit
{
    protected ProcessorConfig $config;
    private FormatterManager $service;
    private MainConfig|MockObject $mockConfig;
    private FormatterConfig $mockServiceConfig;

    // @phpcs:ignore
    public function _before(): void
    {
        parent::_before();
        $mockContainer = $this->createMock(ContainerInterface::class);

        $this->mockConfig = $this->getMockBuilder(MainConfig::class)
            ->disableOriginalConstructor()
            ->getMock();

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
            ->willReturn($this->mockServiceConfig);

        $result = $this->service->getServiceConfig('my-config-name');
        $this->assertEquals($this->mockServiceConfig, $result);
    }

    public function testHasServiceConfig()
    {
        $this->mockConfig->expects($this->once())
            ->method('hasFormatterConfig')
            ->with('my-config-name')
            ->willReturn(true);

        $result = $this->service->hasServiceConfig('my-config-name');
        $this->assertTrue($result);
    }
}
