<?php

declare(strict_types=1);

namespace Sirix\Monolog\Builder;

use Monolog\Formatter\FormatterInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ContainerResolver;
use Sirix\ContainerResolver\Exception\ConfigReaderException;
use Sirix\Monolog\Config\MonologConfig;
use Sirix\Monolog\Exception\ConfigReaderExceptionTranslator;
use Sirix\Monolog\Exception\InvalidConfigException;
use Sirix\Monolog\Exception\InvalidFactoryException;
use Sirix\Monolog\Exception\MissingConfigException;
use Sirix\Monolog\Formatter\FormatterFactoryInterface;

final readonly class FormatterBuilder
{
    public function __construct(private ContainerInterface $container, private MonologConfig $monologConfig) {}

    /**
     * @throws ContainerExceptionInterface
     * @throws InvalidConfigException
     * @throws MissingConfigException
     */
    public function build(string $formatterId): FormatterInterface
    {
        $formatterDefinition = $this->monologConfig->formatter($formatterId);
        $factory             = $this->factory($formatterDefinition->type);

        try {
            return $factory->create($this->container, $formatterDefinition);
        } catch (ConfigReaderException $configReaderException) {
            throw ConfigReaderExceptionTranslator::translate("Formatter '{$formatterId}' ({$formatterDefinition->type})", $configReaderException);
        }
    }

    /**
     * @throws ContainerExceptionInterface
     */
    private function factory(string $type): FormatterFactoryInterface
    {
        $factoryClass = $this->monologConfig->formatterFactory($type);
        $factory      = $this->container->has($factoryClass)
            ? ContainerResolver::forContext($this->container, self::class)->getAs($factoryClass, FormatterFactoryInterface::class)
            : new $factoryClass();

        if (! $factory instanceof FormatterFactoryInterface) {
            throw InvalidFactoryException::forFactory($type, FormatterFactoryInterface::class, $factory);
        }

        return $factory;
    }
}
