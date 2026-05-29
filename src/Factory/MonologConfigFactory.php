<?php

declare(strict_types=1);

namespace Sirix\Monolog\Factory;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ContainerResolver;
use Sirix\Monolog\Config\MonologConfig;
use Sirix\Monolog\Config\MonologConfigReader;

final class MonologConfigFactory
{
    /**
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): MonologConfig
    {
        $resolver = ContainerResolver::forFactory($container, self::class);

        return (new MonologConfigReader())->read($resolver->optionalArray('config'));
    }
}
