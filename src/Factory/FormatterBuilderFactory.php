<?php

declare(strict_types=1);

namespace Sirix\Monolog\Factory;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ContainerResolver;
use Sirix\Monolog\Builder\FormatterBuilder;
use Sirix\Monolog\Config\MonologConfig;

final class FormatterBuilderFactory
{
    /**
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): FormatterBuilder
    {
        return new FormatterBuilder(
            $container,
            ContainerResolver::forFactory($container, self::class)->get(MonologConfig::class),
        );
    }
}
