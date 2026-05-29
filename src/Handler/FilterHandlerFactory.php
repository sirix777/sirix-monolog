<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\FilterHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;

use function is_array;

class FilterHandlerFactory implements HandlerFactoryInterface, HandlerRegistryAwareInterface
{
    use HandlerRegistryTrait;

    public function create(ContainerInterface $container, HandlerDefinition $definition): FilterHandler
    {
        $options = ConfigReader::fromArray($definition->options, self::class);
        $minLevelOrList = $this->levelOrList($definition->options['min_level_or_list'] ?? Level::Debug);
        $maxLevel = $options->enum('max_level', Level::class, Level::Emergency);

        return new FilterHandler(
            $this->getHandlerRegistry()->get($options->requiredNonEmptyString('handler')),
            $minLevelOrList,
            $maxLevel,
            $options->bool('bubble', true),
        );
    }

    private function levelOrList(mixed $value): array|Level
    {
        if (! is_array($value)) {
            $reader = ConfigReader::fromArray(['level' => $value], self::class);

            return $reader->enum('level', Level::class, Level::Debug);
        }

        $levels = [];

        foreach ($value as $index => $level) {
            $reader = ConfigReader::fromArray(['level' => $level], self::class);
            $levels[$index] = $reader->enum('level', Level::class, Level::Debug);
        }

        return $levels;
    }
}
