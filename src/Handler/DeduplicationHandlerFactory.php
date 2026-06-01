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

    public function create(ContainerInterface $container, HandlerDefinition $handlerDefinition): DeduplicationHandler
    {
        $configReader = ConfigReader::fromArray($handlerDefinition->options, self::class);
        $deduplicationLevel = $configReader->enum('deduplication_level', Level::class, Level::Error);

        return new DeduplicationHandler(
            $this->getHandlerRegistry()->get($configReader->requiredNonEmptyString('handler')),
            $configReader->optionalString('deduplication_store'),
            $deduplicationLevel,
            $configReader->int('time', 60),
            $configReader->bool('bubble', true),
        );
    }
}
