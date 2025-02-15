<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Config;

use Sirix\Monolog\Config\FormatterConfig;
use Sirix\Monolog\Exception\MissingConfigException;
use Codeception\Test\Unit;

class FormatterConfigTest extends Unit
{
    protected FormatterConfig $config;

    // phpcs:ignore
    public function _before(): void
    {
        parent::_before();
        $this->config = new FormatterConfig($this->getConfigArray());

        $this->assertInstanceOf(FormatterConfig::class, $this->config);
    }

    public function testConstructorMissingConfig()
    {
        $this->expectException(MissingConfigException::class);
        new FormatterConfig([]);
    }

    public function testConstructorMissingType()
    {
        $this->expectException(MissingConfigException::class);

        $config = $this->getConfigArray();
        unset($config['type']);
        new FormatterConfig($config);
    }

    public function testConstructorMissingOptions()
    {
        $config = $this->getConfigArray();
        unset($config['options']);

        $configService = new FormatterConfig($config);
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
            'type' => 'LineFormatter',
            'options' => [
                'format' => "%datetime% > %level_name% > %message% %context% %extra%\n",
                'dateFormat' => 'Y n j, g:i a',
            ],
        ];
    }
}
