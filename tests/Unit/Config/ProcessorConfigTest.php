<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Config;

use Sirix\Monolog\Config\ProcessorConfig;
use Sirix\Monolog\Exception\MissingConfigException;
use Codeception\Test\Unit;

class ProcessorConfigTest extends Unit
{
    protected ProcessorConfig $config;

    // @phpcs:ignore
    public function _before(): void
    {
        parent::_before();
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
