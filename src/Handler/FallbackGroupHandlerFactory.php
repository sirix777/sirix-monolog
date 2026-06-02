<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\FallbackGroupHandler;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;

class FallbackGroupHandlerFactory implements HandlerFactoryInterface, HandlerRegistryAwareInterface
{
    use HandlerRegistryTrait;

    public function create(ContainerInterface $container, HandlerDefinition $handlerDefinition): FallbackGroupHandler
    {
        $configReader = ConfigReader::fromArray($handlerDefinition->options, self::class);
        $handlers = [];

        foreach ($configReader->requiredNonEmptyStringList('handlers') as $handlerId) {
            $handlers[] = $this->getHandlerRegistry()->get($handlerId);
        }

        return new FallbackGroupHandler(
            $handlers,
            $configReader->bool('bubble', true),
        );
    }
}
