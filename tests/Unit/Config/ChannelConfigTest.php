<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Config;

use Sirix\Monolog\Config\ChannelConfig;
use Sirix\Monolog\Exception\MissingConfigException;
use Codeception\Test\Unit;

class ChannelConfigTest extends Unit
{
    protected ChannelConfig $config;

    // @phpcs:ignore
    public function _before(): void
    {
        parent::_before();
        $this->config = new ChannelConfig($this->getConfigArray());

        $this->assertInstanceOf(ChannelConfig::class, $this->config);
    }

    public function testConstructorMissingConfig()
    {
        $this->expectException(MissingConfigException::class);
        new ChannelConfig([]);
    }

    public function testConstructorMissingHandlers()
    {
        $this->expectException(MissingConfigException::class);
        $config = $this->getConfigArray();
        unset($config['handlers']);
        new ChannelConfig($config);
    }

    public function testConstructorMissingProcessors()
    {
        $config = $this->getConfigArray();
        unset($config['processors']);
        $configService = new ChannelConfig($config);
        $this->assertEmpty($configService->getProcessors());
    }

    public function testGetHandlers()
    {
        $config = $this->getConfigArray();
        $results = $this->config->getHandlers();
        $this->assertEquals($config['handlers'], $results);
    }

    public function testGetProcessors()
    {
        $config = $this->getConfigArray();
        $results = $this->config->getProcessors();
        $this->assertEquals($config['processors'], $results);
    }

    public function testGetName()
    {
        $config = $this->getConfigArray();
        $result = $this->config->getName();
        $this->assertEquals($config['name'], $result);
    }

    public function testGetNameReturnsNull()
    {
        $config = $this->getConfigArray();
        unset($config['name']);
        $this->config = new ChannelConfig($config);
        $result = $this->config->getName();
        $this->assertNull($result);
    }

    private function getConfigArray(): array
    {
        return [
            'handlers' => [
                'handlerOne',
                'handlerTwo',
            ],
            'processors' => [
                'serviceOne',
                'serviceTwo',
            ],
            'name' => 'testChannel',
        ];
    }
}
