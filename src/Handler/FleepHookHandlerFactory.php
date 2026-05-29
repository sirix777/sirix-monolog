<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\FleepHookHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;

class FleepHookHandlerFactory implements HandlerFactoryInterface
{
    use HandlerOptionTrait;

    public function create(ContainerInterface $container, HandlerDefinition $definition): FleepHookHandler
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        return new FleepHookHandler(
            $options->requiredNonEmptyString('token'),
            $options->enum('level', Level::class, Level::Debug),
            $options->bool('bubble', true),
            $options->bool('persistent', false),
            $this->floatOption($definition->options, 'timeout', 0.0, 'Fleep hook'),
            $this->floatOption($definition->options, 'writing_timeout', 10.0, 'Fleep hook'),
            $this->nullableFloatOption($definition->options, 'connection_timeout', 'Fleep hook'),
            $this->nullableIntOption($definition->options, 'chunk_size', 'Fleep hook'),
        );
    }
}
