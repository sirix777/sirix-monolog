<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Config;

use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Config\ProcessorConfig;
use Sirix\Monolog\Exception\MissingConfigException;

class ProcessorConfigTest extends TestCase
{
    protected ProcessorConfig $config;

    public function setUp(): void
    {
        $this->config = new ProcessorConfig($this->getConfigArray());

        $this->assertInstanceOf(ProcessorConfig::class, $this->config);
    }

    public function testConstructorMissingConfig()
    {
        $this->expectException(MissingConfigException::class);
        new ProcessorConfig([]);
    }

    public function testConstructorMissingType()
    {
        $this->expectException(MissingConfigException::class);

        $config = $this->getConfigArray();
        unset($config['type']);
        new ProcessorConfig($config);
    }

    public function testConstructorMissingOptions()
    {
        $config = $this->getConfigArray();
        unset($config['options']);

        $configService = new ProcessorConfig($config);
        $this->assertEmpty($configService->getOptions());
    }

    public function testGetType()
    {
        $config = $this->getConfigArray();
        $type = $this->config->getType();
        $this->assertEquals($config['type'], $type);
    }

    public function testGetOptions()
    {
        $config = $this->getConfigArray();
        $type = $this->config->getOptions();
        $this->assertEquals($config['options'], $type);
    }

    private function getConfigArray(): array
    {
        return [
            'type' => 'Processor',
            'options' => [
                'dateFormat' => 'Y n j, g:i a',
            ],
        ];
    }
}
