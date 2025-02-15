<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Config;

use Monolog\Level;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Config\HandlerConfig;
use Sirix\Monolog\Exception\MissingConfigException;

class HandlerConfigTest extends TestCase
{
    protected HandlerConfig $config;

    public function setUp(): void
    {
        $this->config = new HandlerConfig($this->getConfigArray());

        $this->assertInstanceOf(HandlerConfig::class, $this->config);
    }

    public function testConstructorMissingConfig()
    {
        $this->expectException(MissingConfigException::class);
        new HandlerConfig([]);
    }

    public function testConstructorMissingType()
    {
        $this->expectException(MissingConfigException::class);

        $config = $this->getConfigArray();
        unset($config['type']);
        new HandlerConfig($config);
    }

    public function testConstructorMissingOptions()
    {
        $config = $this->getConfigArray();
        unset($config['options']);

        $configService = new HandlerConfig($config);
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

    public function testGetFormatter()
    {
        $config = $this->getConfigArray();
        $formatter = $this->config->getFormatter();
        $this->assertEquals($config['formatter'], $formatter);
    }

    public function testGetFormatterMissingFormatter()
    {
        $config = $this->getConfigArray();
        unset($config['formatter']);
        $configService = new HandlerConfig($config);
        $formatter = $configService->getFormatter();
        $this->assertEmpty($formatter);
    }

    public function testGetProcessors()
    {
        $config = $this->getConfigArray();
        $processors = $this->config->getProcessors();
        $this->assertEquals($config['processors'], $processors);
    }

    private function getConfigArray(): array
    {
        return [
            'type' => 'StreamHandler',
            'formatter' => 'formatterOne',
            'options' => [
                'stream' => '/tmp/logOne.txt',
                'level' => Level::Error,
                'bubble' => true,
                'filePermission' => 755,
                'useLocking' => true,
            ],
            'processors' => [
                'processorOne',
                'processorTwo',
            ],
        ];
    }
}
