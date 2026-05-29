<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\DeduplicationHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;

class DeduplicationHandlerFactory implements HandlerFactoryInterface, HandlerRegistryAwareInterface
{
    use HandlerRegistryTrait;

    public function create(ContainerInterface $container, HandlerDefinition $definition): DeduplicationHandler
    {
        $options = ConfigReader::fromArray($definition->options, self::class);
        $deduplicationLevel = $options->enum('deduplication_level', Level::class, Level::Error);

        return new DeduplicationHandler(
            $this->getHandlerRegistry()->get($options->requiredNonEmptyString('handler')),
            $options->optionalString('deduplication_store'),
            $deduplicationLevel,
            $options->int('time', 60),
            $options->bool('bubble', true),
        );
    }
}
