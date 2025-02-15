<?php

declare(strict_types=1);

namespace Sirix\Monolog\Service;

use Psr\Container\ContainerInterface;
use Sirix\Monolog\Config\MainConfig;
use Sirix\Monolog\ConfigInterface;
use Sirix\Monolog\ContainerAwareInterface;
use Sirix\Monolog\Exception\InvalidConfigException;
use Sirix\Monolog\Exception\MissingConfigException;
use Sirix\Monolog\Exception\UnknownServiceException;
use Sirix\Monolog\FactoryInterface;
use Sirix\Monolog\HandlerManagerAwareInterface;
use Sirix\Monolog\MapperInterface;

use function array_key_exists;
use function class_exists;
use function class_implements;
use function in_array;

abstract class AbstractServiceManager implements ContainerInterface
{
    protected array $services = [];

    public function __construct(
        protected MainConfig $config,
        protected MapperInterface $mapper,
        protected ContainerInterface $container
    ) {}

    public function get(string $id): mixed
    {
        if (array_key_exists($id, $this->services)) {
            return $this->services[$id];
        }

        if (! $this->has($id)) {
            throw new UnknownServiceException(
                "Unable to locate service {$id}. Please check your configuration."
            );
        }

        if ($this->container->has($id)) {
            $this->services[$id] = $this->container->get($id);

            return $this->services[$id];
        }

        $this->services[$id] = $this->getInstanceFromFactory($id);

        return $this->services[$id];
    }

    public function has(string $id): bool
    {
        if (array_key_exists($id, $this->services)) {
            return true;
        }

        if ($this->container->has($id)) {
            return true;
        }

        return $this->hasServiceConfig($id);
    }

    abstract protected function getServiceConfig(string $id): ?ConfigInterface;

    abstract protected function hasServiceConfig(string $id): bool;

    protected function getInstanceFromFactory(string $id): mixed
    {
        $config = $this->getServiceConfig($id);

        if (! $config instanceof ConfigInterface) {
            throw new MissingConfigException(
                'Unable to find service config'
            );
        }

        $type = $config->getType();
        $options = $config->getOptions();

        $class = $type;

        if (
            ! class_exists($class)
            || ! in_array(FactoryInterface::class, class_implements($class))
        ) {
            $class = $this->mapper->map($type);
        }

        if (
            ! $class
            || ! class_exists($class)
            || ! in_array(FactoryInterface::class, class_implements($class))
        ) {
            throw new InvalidConfigException(
                "{$id}. Is not a valid factory. Please check your configuration."
            );
        }

        /**
         * @var FactoryInterface $factory
         */
        $factory = new $class();

        if ($factory instanceof ContainerAwareInterface) {
            $factory->setContainer($this->container);
        }

        if (
            $factory instanceof HandlerManagerAwareInterface
            && $this instanceof HandlerManager
        ) {
            $factory->setHandlerManager($this);
        }

        return $factory($options);
    }
}
