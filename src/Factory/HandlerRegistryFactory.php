<?php

declare(strict_types=1);

namespace Sirix\Monolog\Factory;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ContainerResolver;
use Sirix\Monolog\Builder\HandlerBuilder;
use Sirix\Monolog\Registry\HandlerRegistry;

final class HandlerRegistryFactory
{
    /**
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): HandlerRegistry
    {
        return new HandlerRegistry(
            ContainerResolver::forFactory($container, self::class)->get(HandlerBuilder::class),
        );
    }
}
