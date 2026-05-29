<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\WhatFailureGroupHandler;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;

class WhatFailureGroupHandlerFactory implements HandlerFactoryInterface, HandlerRegistryAwareInterface
{
    use HandlerRegistryTrait;

    public function create(ContainerInterface $container, HandlerDefinition $definition): WhatFailureGroupHandler
    {
        $options = ConfigReader::fromArray($definition->options, self::class);
        $handlers = [];

        foreach ($options->requiredNonEmptyStringList('handlers') as $handlerId) {
            $handlers[] = $this->getHandlerRegistry()->get($handlerId);
        }

        return new WhatFailureGroupHandler(
            $handlers,
            $options->bool('bubble', true),
        );
    }
}
