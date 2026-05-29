<?php

declare(strict_types=1);

namespace Sirix\Monolog\Factory;

use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ContainerResolver;
use Sirix\Monolog\Builder\ProcessorBuilder;
use Sirix\Monolog\Config\MonologConfig;

final class ProcessorBuilderFactory
{
    public function __invoke(ContainerInterface $container): ProcessorBuilder
    {
        return new ProcessorBuilder(
            $container,
            ContainerResolver::forFactory($container, self::class)->get(MonologConfig::class),
        );
    }
}
