<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\OverflowHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;

class OverflowHandlerFactory implements HandlerFactoryInterface, HandlerRegistryAwareInterface
{
    use HandlerRegistryTrait;

    public function create(ContainerInterface $container, HandlerDefinition $definition): OverflowHandler
    {
        $options = ConfigReader::fromArray($definition->options, self::class);
        $level = $options->enum('level', Level::class, Level::Debug);

        return new OverflowHandler(
            $this->getHandlerRegistry()->get($options->requiredNonEmptyString('handler')),
            $this->thresholdMap($options->map('threshold_map', [])),
            $level,
            $options->bool('bubble', true),
        );
    }

    /**
     * @param array<string, mixed> $thresholdMap
     *
     * @return array<int, int>
     */
    private function thresholdMap(array $thresholdMap): array
    {
        $levels = [
            'debug' => Level::Debug,
            'info' => Level::Info,
            'notice' => Level::Notice,
            'warning' => Level::Warning,
            'error' => Level::Error,
            'critical' => Level::Critical,
            'alert' => Level::Alert,
            'emergency' => Level::Emergency,
        ];

        $result = [];

        foreach ($levels as $name => $level) {
            $reader = ConfigReader::fromArray(['threshold' => $thresholdMap[$name] ?? 0], self::class);
            $result[$level->value] = $reader->int('threshold', 0);
        }

        return $result;
    }
}
