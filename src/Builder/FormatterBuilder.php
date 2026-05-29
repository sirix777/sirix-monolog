<?php

declare(strict_types=1);

namespace Sirix\Monolog\Builder;

use Monolog\Formatter\FormatterInterface;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ContainerResolver;
use Sirix\Monolog\Config\MonologConfig;
use Sirix\Monolog\Exception\InvalidFactoryException;
use Sirix\Monolog\Formatter\FormatterFactoryInterface;

final readonly class FormatterBuilder
{
    public function __construct(private ContainerInterface $container, private MonologConfig $config) {}

    public function build(string $formatterId): FormatterInterface
    {
        $definition = $this->config->formatter($formatterId);
        $factory = $this->factory($definition->type);

        return $factory->create($this->container, $definition);
    }

    private function factory(string $type): FormatterFactoryInterface
    {
        $factoryClass = $this->config->formatterFactory($type);
        $factory = $this->container->has($factoryClass)
            ? ContainerResolver::forContext($this->container, self::class)->getAs($factoryClass, FormatterFactoryInterface::class)
            : new $factoryClass();

        if (! $factory instanceof FormatterFactoryInterface) {
            throw InvalidFactoryException::forFactory($type, FormatterFactoryInterface::class, $factory);
        }

        return $factory;
    }
}
