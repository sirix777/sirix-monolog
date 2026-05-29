<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\FingersCrossed\ActivationStrategyInterface;
use Monolog\Handler\FingersCrossedHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\ContainerResolver\ContainerResolver;
use Sirix\Monolog\Config\HandlerDefinition;

use function is_string;

class FingersCrossedHandlerFactory implements HandlerFactoryInterface, HandlerRegistryAwareInterface
{
    use HandlerRegistryTrait;

    public function create(ContainerInterface $container, HandlerDefinition $definition): FingersCrossedHandler
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        return new FingersCrossedHandler(
            $this->getHandlerRegistry()->get($options->requiredNonEmptyString('handler')),
            $this->activationStrategy($container, $definition->options['activation_strategy'] ?? null),
            $options->int('buffer_size', 0),
            $options->bool('bubble', true),
            $options->bool('stop_buffering', true),
            $this->passthruLevel($definition->options['passthru_level'] ?? null),
        );
    }

    private function activationStrategy(
        ContainerInterface $container,
        mixed $value
    ): ActivationStrategyInterface|Level|null {
        if (null === $value) {
            return null;
        }

        if (is_string($value) && $container->has($value)) {
            $strategy = ContainerResolver::forContext($container, self::class)->getExisting($value);

            if ($strategy instanceof ActivationStrategyInterface) {
                return $strategy;
            }
        }

        $reader = ConfigReader::fromArray(['level' => $value], self::class);

        return $reader->enum('level', Level::class, Level::Warning);
    }

    private function passthruLevel(mixed $value): ?Level
    {
        if (null === $value) {
            return null;
        }

        $reader = ConfigReader::fromArray(['level' => $value], self::class);

        return $reader->enum('level', Level::class, Level::Debug);
    }
}
