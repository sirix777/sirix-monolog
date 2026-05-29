<?php

declare(strict_types=1);

namespace Sirix\Monolog\Factory;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ContainerResolver;
use Sirix\Monolog\Builder\LoggerBuilder;
use Sirix\Monolog\Config\MonologConfig;
use Sirix\Monolog\Registry\HandlerRegistry;
use Sirix\Monolog\Registry\ProcessorRegistry;

final class LoggerBuilderFactory
{
    /**
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): LoggerBuilder
    {
        $resolver = ContainerResolver::forFactory($container, self::class);

        return new LoggerBuilder(
            $resolver->get(MonologConfig::class),
            $resolver->get(HandlerRegistry::class),
            $resolver->get(ProcessorRegistry::class),
        );
    }
}
