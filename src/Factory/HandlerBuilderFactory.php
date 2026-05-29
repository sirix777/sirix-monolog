<?php

declare(strict_types=1);

namespace Sirix\Monolog\Factory;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ContainerResolver;
use Sirix\Monolog\Builder\HandlerBuilder;
use Sirix\Monolog\Config\MonologConfig;
use Sirix\Monolog\Registry\FormatterRegistry;
use Sirix\Monolog\Registry\ProcessorRegistry;

final class HandlerBuilderFactory
{
    /**
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): HandlerBuilder
    {
        $resolver = ContainerResolver::forFactory($container, self::class);

        return new HandlerBuilder(
            $container,
            $resolver->get(MonologConfig::class),
            $resolver->get(FormatterRegistry::class),
            $resolver->get(ProcessorRegistry::class),
        );
    }
}
