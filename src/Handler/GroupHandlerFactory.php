<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\GroupHandler;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;

class GroupHandlerFactory implements HandlerFactoryInterface, HandlerRegistryAwareInterface
{
    use HandlerRegistryTrait;

    public function create(ContainerInterface $container, HandlerDefinition $definition): GroupHandler
    {
        $options = ConfigReader::fromArray($definition->options, self::class);
        $handlers = [];

        foreach ($options->requiredNonEmptyStringList('handlers') as $handlerId) {
            $handlers[] = $this->getHandlerRegistry()->get($handlerId);
        }

        return new GroupHandler(
            $handlers,
            $options->bool('bubble', true),
        );
    }
}
