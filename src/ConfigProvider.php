<?php

declare(strict_types=1);

namespace Sirix\Monolog;

use Psr\Log\LoggerInterface;
use Sirix\Monolog\Builder\FormatterBuilder;
use Sirix\Monolog\Builder\HandlerBuilder;
use Sirix\Monolog\Builder\LoggerBuilder;
use Sirix\Monolog\Builder\ProcessorBuilder;
use Sirix\Monolog\Config\MonologConfig;
use Sirix\Monolog\Factory\ChannelRegistryFactory;
use Sirix\Monolog\Factory\FormatterBuilderFactory;
use Sirix\Monolog\Factory\FormatterRegistryFactory;
use Sirix\Monolog\Factory\HandlerBuilderFactory;
use Sirix\Monolog\Factory\HandlerRegistryFactory;
use Sirix\Monolog\Factory\LoggerBuilderFactory;
use Sirix\Monolog\Factory\LoggerFactory;
use Sirix\Monolog\Factory\MonologConfigFactory;
use Sirix\Monolog\Factory\ProcessorBuilderFactory;
use Sirix\Monolog\Factory\ProcessorRegistryFactory;
use Sirix\Monolog\Registry\ChannelRegistry;
use Sirix\Monolog\Registry\FormatterRegistry;
use Sirix\Monolog\Registry\HandlerRegistry;
use Sirix\Monolog\Registry\ProcessorRegistry;

final class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->dependencies(),
        ];
    }

    public function dependencies(): array
    {
        return [
            'aliases' => [
                LoggerInterface::class => 'logger.default',
            ],
            'factories' => [
                'logger.default' => LoggerFactory::class,
                MonologConfig::class => MonologConfigFactory::class,
                ChannelRegistry::class => ChannelRegistryFactory::class,
                HandlerRegistry::class => HandlerRegistryFactory::class,
                FormatterRegistry::class => FormatterRegistryFactory::class,
                ProcessorRegistry::class => ProcessorRegistryFactory::class,
                LoggerBuilder::class => LoggerBuilderFactory::class,
                HandlerBuilder::class => HandlerBuilderFactory::class,
                FormatterBuilder::class => FormatterBuilderFactory::class,
                ProcessorBuilder::class => ProcessorBuilderFactory::class,
            ],
        ];
    }
}
