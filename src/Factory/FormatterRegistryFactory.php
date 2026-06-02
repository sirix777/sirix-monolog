<?php

declare(strict_types=1);

namespace Sirix\Monolog\Factory;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ContainerResolver;
use Sirix\Monolog\Builder\FormatterBuilder;
use Sirix\Monolog\Registry\FormatterRegistry;

final class FormatterRegistryFactory
{
    /**
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): FormatterRegistry
    {
        return new FormatterRegistry(
            ContainerResolver::forFactory($container, self::class)->get(FormatterBuilder::class),
        );
    }
}
