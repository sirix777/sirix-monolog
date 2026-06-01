<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\HandlerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;
use Sirix\ContainerResolver\ContainerResolver;
use Sirix\Monolog\Exception\InvalidConfigException;

use function class_exists;
use function implode;
use function interface_exists;
use function is_object;
use function is_string;

trait ReflectiveHandlerFactoryTrait
{
    /**
     * @param class-string $handlerClass
     * @param list<mixed>  $arguments
     *
     * @throws ReflectionException
     */
    private function newHandler(string $handlerClass, array $arguments): HandlerInterface
    {
        $handler = (new ReflectionClass($handlerClass))->newInstanceArgs($arguments);

        if (! $handler instanceof HandlerInterface) {
            throw new InvalidConfigException("Handler class {$handlerClass} must create a Monolog handler.");
        }

        return $handler;
    }

    /**
     * @param list<string> $expectedClasses
     *
     * @throws ContainerExceptionInterface
     */
    private function serviceObject(
        ContainerInterface $container,
        mixed $value,
        string $option,
        string $handler,
        array $expectedClasses
    ): object {
        if (is_string($value) && $container->has($value)) {
            $value = ContainerResolver::forContext($container, self::class)->getExisting($value);
        }

        if (! is_object($value)) {
            throw new InvalidConfigException("{$handler} handler option '{$option}' must be an object or container service id.");
        }

        $availableExpectedClasses = [];
        foreach ($expectedClasses as $expectedClass) {
            if (class_exists($expectedClass) || interface_exists($expectedClass)) {
                $availableExpectedClasses[] = $expectedClass;
            }
        }

        if ([] === $availableExpectedClasses) {
            throw new InvalidConfigException("{$handler} handler requires one of these optional classes: " . implode(', ', $expectedClasses) . '.');
        }

        foreach ($availableExpectedClasses as $availableExpectedClass) {
            if ($value instanceof $availableExpectedClass) {
                return $value;
            }
        }

        throw new InvalidConfigException("{$handler} handler option '{$option}' must resolve to one of: " . implode(', ', $availableExpectedClasses) . '.');
    }

    /**
     * @throws ContainerExceptionInterface
     */
    private function optionalServiceObject(
        ContainerInterface $container,
        mixed $value,
        string $option,
        string $handler,
        string $expectedClass
    ): ?object {
        if (null === $value) {
            return null;
        }

        return $this->serviceObject($container, $value, $option, $handler, [$expectedClass]);
    }
}
