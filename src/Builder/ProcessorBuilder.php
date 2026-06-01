<?php

declare(strict_types=1);

namespace Sirix\Monolog\Builder;

use Monolog\Processor\ProcessorInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ContainerResolver;
use Sirix\Monolog\Config\MonologConfig;
use Sirix\Monolog\Exception\InvalidFactoryException;
use Sirix\Monolog\Processor\ProcessorFactoryInterface;

final readonly class ProcessorBuilder
{
    public function __construct(private ContainerInterface $container, private MonologConfig $monologConfig) {}

    /**
     * @throws ContainerExceptionInterface
     */
    public function build(string $processorId): ProcessorInterface
    {
        $processorDefinition = $this->monologConfig->processor($processorId);
        $factory = $this->factory($processorDefinition->type);

        return $factory->create($this->container, $processorDefinition);
    }

    /**
     * @throws ContainerExceptionInterface
     */
    private function factory(string $type): ProcessorFactoryInterface
    {
        $factoryClass = $this->monologConfig->processorFactory($type);
        $factory = $this->container->has($factoryClass)
            ? ContainerResolver::forContext($this->container, self::class)->getAs($factoryClass, ProcessorFactoryInterface::class)
            : new $factoryClass();

        if (! $factory instanceof ProcessorFactoryInterface) {
            throw InvalidFactoryException::forFactory($type, ProcessorFactoryInterface::class, $factory);
        }

        return $factory;
    }
}
