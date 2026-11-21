<?php

declare(strict_types=1);

namespace Sirix\Monolog\Builder;

use Monolog\Processor\ProcessorInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ContainerResolver;
use Sirix\ContainerResolver\Exception\ConfigReaderException;
use Sirix\Monolog\Config\MonologConfig;
use Sirix\Monolog\Exception\ConfigReaderExceptionTranslator;
use Sirix\Monolog\Exception\InvalidConfigException;
use Sirix\Monolog\Exception\InvalidFactoryException;
use Sirix\Monolog\Exception\MissingConfigException;
use Sirix\Monolog\Processor\ProcessorFactoryInterface;

final readonly class ProcessorBuilder
{
    public function __construct(private ContainerInterface $container, private MonologConfig $monologConfig) {}

    /**
     * @throws ContainerExceptionInterface
     * @throws InvalidConfigException
     * @throws MissingConfigException
     */
    public function build(string $processorId): ProcessorInterface
    {
        $processorDefinition = $this->monologConfig->processor($processorId);
        $factory             = $this->factory($processorDefinition->type);

        try {
            return $factory->create($this->container, $processorDefinition);
        } catch (ConfigReaderException $configReaderException) {
            throw ConfigReaderExceptionTranslator::translate("Processor '{$processorId}' ({$processorDefinition->type})", $configReaderException);
        }
    }

    /**
     * @throws ContainerExceptionInterface
     */
    private function factory(string $type): ProcessorFactoryInterface
    {
        $factoryClass = $this->monologConfig->processorFactory($type);
        $factory      = $this->container->has($factoryClass)
            ? ContainerResolver::forContext($this->container, self::class)->getAs($factoryClass, ProcessorFactoryInterface::class)
            : new $factoryClass();

        if (! $factory instanceof ProcessorFactoryInterface) {
            throw InvalidFactoryException::forFactory($type, ProcessorFactoryInterface::class, $factory);
        }

        return $factory;
    }
}
