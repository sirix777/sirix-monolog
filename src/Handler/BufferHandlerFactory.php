<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\BufferHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;

class BufferHandlerFactory implements HandlerFactoryInterface, HandlerRegistryAwareInterface
{
    use HandlerRegistryTrait;

    public function create(ContainerInterface $container, HandlerDefinition $handlerDefinition): BufferHandler
    {
        $configReader = ConfigReader::fromArray($handlerDefinition->options, self::class);
        $level = $configReader->enum('level', Level::class, Level::Debug);

        return new BufferHandler(
            $this->getHandlerRegistry()->get($configReader->requiredNonEmptyString('handler')),
            $configReader->int('buffer_limit', 0),
            $level,
            $configReader->bool('bubble', true),
            $configReader->bool('flush_on_overflow', false),
        );
    }
}
