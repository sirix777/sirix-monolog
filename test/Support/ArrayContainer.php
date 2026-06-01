<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Support;

use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionFunction;
use ReflectionMethod;
use RuntimeException;

use function array_key_exists;
use function is_string;

final class ArrayContainer implements ContainerInterface
{
    /**
     * @param array<string, mixed>                              $services
     * @param array<string, callable(self): mixed|class-string> $factories
     * @param array<string, string>                             $aliases
     */
    public function __construct(private array $services = [], private array $factories = [], private array $aliases = []) {}

    public static function fromConfigProvider(array $appConfig, array $providerConfig): self
    {
        $dependencies = $providerConfig['dependencies'] ?? [];

        return new self(
            services: ['config' => $appConfig],
            factories: $dependencies['factories'] ?? [],
            aliases: $dependencies['aliases'] ?? [],
        );
    }

    public function get(string $id): mixed
    {
        $id = $this->aliases[$id] ?? $id;

        if (array_key_exists($id, $this->services)) {
            return $this->services[$id];
        }

        if (! array_key_exists($id, $this->factories)) {
            throw new class("Service '{$id}' was not found.") extends RuntimeException implements NotFoundExceptionInterface {};
        }

        $factory = $this->factories[$id];
        $this->services[$id] = $this->create($factory, $id);

        return $this->services[$id];
    }

    public function has(string $id): bool
    {
        $id = $this->aliases[$id] ?? $id;

        return array_key_exists($id, $this->services) || array_key_exists($id, $this->factories);
    }

    /**
     * @param callable(self): mixed|class-string $factory
     */
    private function create(callable|string $factory, string $requestedName): mixed
    {
        if (is_string($factory)) {
            $factory = new $factory();
            $reflection = new ReflectionMethod($factory, '__invoke');

            return $reflection->getNumberOfParameters() > 1
                ? $factory($this, $requestedName)
                : $factory($this);
        }

        $reflection = new ReflectionFunction($factory);

        return $reflection->getNumberOfParameters() > 1
            ? $factory($this, $requestedName)
            : $factory($this);
    }
}
