<?php

declare(strict_types=1);

namespace Sirix\Monolog\Builder;

use Monolog\Handler\HandlerInterface;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ContainerResolver;
use Sirix\Monolog\Config\MonologConfig;
use Sirix\Monolog\Exception\InvalidFactoryException;
use Sirix\Monolog\Exception\MissingServiceException;
use Sirix\Monolog\Handler\HandlerFactoryInterface;
use Sirix\Monolog\Handler\HandlerRegistryAwareInterface;
use Sirix\Monolog\Registry\FormatterRegistry;
use Sirix\Monolog\Registry\HandlerRegistry;
use Sirix\Monolog\Registry\ProcessorRegistry;

use function method_exists;

final class HandlerBuilder
{
    private ?HandlerRegistry $handlerRegistry = null;

    public function __construct(
        private readonly ContainerInterface $container,
        private readonly MonologConfig $config,
        private readonly FormatterRegistry $formatters,
        private readonly ProcessorRegistry $processors,
    ) {}

    public function build(string $handlerId): HandlerInterface
    {
        $definition = $this->config->handler($handlerId);
        $factory = $this->factory($definition->type);
        $handler = $factory->create($this->container, $definition);

        if (null !== $definition->formatter && method_exists($handler, 'setFormatter')) {
            $handler->setFormatter($this->formatters->get($definition->formatter));
        }

        if (method_exists($handler, 'pushProcessor')) {
            foreach ($definition->processors as $processorId) {
                $handler->pushProcessor($this->processors->get($processorId));
            }
        }

        return $handler;
    }

    public function setHandlerRegistry(HandlerRegistry $handlerRegistry): void
    {
        $this->handlerRegistry = $handlerRegistry;
    }

    private function getHandlerRegistry(): HandlerRegistry
    {
        if (! $this->handlerRegistry instanceof HandlerRegistry) {
            throw new MissingServiceException('Unable to get HandlerRegistry.');
        }

        return $this->handlerRegistry;
    }

    private function factory(string $type): HandlerFactoryInterface
    {
        $factoryClass = $this->config->handlerFactory($type);
        $factory = $this->container->has($factoryClass)
            ? ContainerResolver::forContext($this->container, self::class)->getAs($factoryClass, HandlerFactoryInterface::class)
            : new $factoryClass();

        if (! $factory instanceof HandlerFactoryInterface) {
            throw InvalidFactoryException::forFactory($type, HandlerFactoryInterface::class, $factory);
        }

        if ($factory instanceof HandlerRegistryAwareInterface) {
            $factory->setHandlerRegistry($this->getHandlerRegistry());
        }

        return $factory;
    }
}
