<?php

declare(strict_types=1);

namespace Sirix\Monolog\Builder;

use Monolog\Processor\ProcessorInterface;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ContainerResolver;
use Sirix\Monolog\Config\MonologConfig;
use Sirix\Monolog\Exception\InvalidFactoryException;
use Sirix\Monolog\Processor\ProcessorFactoryInterface;

final readonly class ProcessorBuilder
{
    public function __construct(private ContainerInterface $container, private MonologConfig $config) {}

    public function build(string $processorId): ProcessorInterface
    {
        $definition = $this->config->processor($processorId);
        $factory = $this->factory($definition->type);

        return $factory->create($this->container, $definition);
    }

    private function factory(string $type): ProcessorFactoryInterface
    {
        $factoryClass = $this->config->processorFactory($type);
        $factory = $this->container->has($factoryClass)
            ? ContainerResolver::forContext($this->container, self::class)->getAs($factoryClass, ProcessorFactoryInterface::class)
            : new $factoryClass();

        if (! $factory instanceof ProcessorFactoryInterface) {
            throw InvalidFactoryException::forFactory($type, ProcessorFactoryInterface::class, $factory);
        }

        return $factory;
    }
}
