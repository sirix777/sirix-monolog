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

    public function create(ContainerInterface $container, HandlerDefinition $definition): BufferHandler
    {
        $options = ConfigReader::fromArray($definition->options, self::class);
        $level = $options->enum('level', Level::class, Level::Debug);

        return new BufferHandler(
            $this->getHandlerRegistry()->get($options->requiredNonEmptyString('handler')),
            $options->int('buffer_limit', 0),
            $level,
            $options->bool('bubble', true),
            $options->bool('flush_on_overflow', false),
        );
    }
}
