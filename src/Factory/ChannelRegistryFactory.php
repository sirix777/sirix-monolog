<?php

declare(strict_types=1);

namespace Sirix\Monolog\Factory;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ContainerResolver;
use Sirix\Monolog\Builder\LoggerBuilder;
use Sirix\Monolog\Registry\ChannelRegistry;

final class ChannelRegistryFactory
{
    /**
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ChannelRegistry
    {
        return new ChannelRegistry(
            ContainerResolver::forFactory($container, self::class)->get(LoggerBuilder::class),
        );
    }
}
