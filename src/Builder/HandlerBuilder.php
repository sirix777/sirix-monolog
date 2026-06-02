<?php

declare(strict_types=1);

namespace Sirix\Monolog\Builder;

use Monolog\Handler\FormattableHandlerInterface;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\ProcessableHandlerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ContainerResolver;
use Sirix\Monolog\Config\MonologConfig;
use Sirix\Monolog\Exception\InvalidConfigException;
use Sirix\Monolog\Exception\InvalidFactoryException;
use Sirix\Monolog\Exception\MissingServiceException;
use Sirix\Monolog\Handler\HandlerFactoryInterface;
use Sirix\Monolog\Handler\HandlerRegistryAwareInterface;
use Sirix\Monolog\Registry\FormatterRegistry;
use Sirix\Monolog\Registry\HandlerRegistry;
use Sirix\Monolog\Registry\ProcessorRegistry;

use function array_reverse;

final class HandlerBuilder
{
    private ?HandlerRegistry $handlerRegistry = null;

    public function __construct(
        private readonly ContainerInterface $container,
        private readonly MonologConfig $monologConfig,
        private readonly FormatterRegistry $formatterRegistry,
        private readonly ProcessorRegistry $processorRegistry,
    ) {}

    /**
     * @throws ContainerExceptionInterface
     */
    public function build(string $handlerId): HandlerInterface
    {
        $handlerDefinition = $this->monologConfig->handler($handlerId);
        $factory = $this->factory($handlerDefinition->type);
        $handler = $factory->create($this->container, $handlerDefinition);

        if (null !== $handlerDefinition->formatter) {
            if (! $handler instanceof FormattableHandlerInterface) {
                throw new InvalidConfigException(
                    "Handler '{$handlerId}' has a formatter configured but does not support formatters.",
                );
            }

            $handler->setFormatter($this->formatterRegistry->get($handlerDefinition->formatter));
        }

        if ([] !== $handlerDefinition->processors) {
            if (! $handler instanceof ProcessableHandlerInterface) {
                throw new InvalidConfigException(
                    "Handler '{$handlerId}' has processors configured but does not support processors.",
                );
            }

            foreach (array_reverse($handlerDefinition->processors) as $processorId) {
                $handler->pushProcessor($this->processorRegistry->get($processorId));
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

    /**
     * @throws ContainerExceptionInterface
     */
    private function factory(string $type): HandlerFactoryInterface
    {
        $factoryClass = $this->monologConfig->handlerFactory($type);
        $factory = $this->container->has($factoryClass)
            ? ContainerResolver::forContext($this->container, self::class)->getAs(
                $factoryClass,
                HandlerFactoryInterface::class,
            )
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
