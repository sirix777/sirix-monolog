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

    public function create(ContainerInterface $container, HandlerDefinition $handlerDefinition): FingersCrossedHandler
    {
        $configReader = ConfigReader::fromArray($handlerDefinition->options, self::class);

        return new FingersCrossedHandler(
            $this->getHandlerRegistry()->get($configReader->requiredNonEmptyString('handler')),
            $this->activationStrategy($container, $handlerDefinition->options['activation_strategy'] ?? null),
            $configReader->int('buffer_size', 0),
            $configReader->bool('bubble', true),
            $configReader->bool('stop_buffering', true),
            $this->passthruLevel($handlerDefinition->options['passthru_level'] ?? null),
        );
    }

    private function activationStrategy(ContainerInterface $container, mixed $value): ActivationStrategyInterface|Level|null
    {
        if (null === $value) {
            return null;
        }

        if (is_string($value) && $container->has($value)) {
            $strategy = ContainerResolver::forContext($container, self::class)->getExisting($value);

            if ($strategy instanceof ActivationStrategyInterface) {
                return $strategy;
            }
        }

        $configReader = ConfigReader::fromArray(['level' => $value], self::class);

        return $configReader->enum('level', Level::class, Level::Warning);
    }

    private function passthruLevel(mixed $value): ?Level
    {
        if (null === $value) {
            return null;
        }

        $configReader = ConfigReader::fromArray(['level' => $value], self::class);

        return $configReader->enum('level', Level::class, Level::Debug);
    }
}
