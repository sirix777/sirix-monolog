<?php

declare(strict_types=1);

namespace Sirix\Monolog\Factory;

use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ContainerResolver;
use Sirix\Monolog\Builder\ProcessorBuilder;
use Sirix\Monolog\Registry\ProcessorRegistry;

final class ProcessorRegistryFactory
{
    public function __invoke(ContainerInterface $container): ProcessorRegistry
    {
        return new ProcessorRegistry(
            ContainerResolver::forFactory($container, self::class)->get(ProcessorBuilder::class),
        );
    }
}
