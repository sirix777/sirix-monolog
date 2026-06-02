<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\FleepHookHandler;
use Monolog\Handler\MissingExtensionException;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;

class FleepHookHandlerFactory implements HandlerFactoryInterface
{
    use HandlerOptionTrait;

    /**
     * @throws MissingExtensionException
     */
    public function create(ContainerInterface $container, HandlerDefinition $handlerDefinition): FleepHookHandler
    {
        $configReader = ConfigReader::fromArray($handlerDefinition->options, self::class);

        return new FleepHookHandler(
            $configReader->requiredNonEmptyString('token'),
            $configReader->enum('level', Level::class, Level::Debug),
            $configReader->bool('bubble', true),
            $configReader->bool('persistent', false),
            $this->floatOption($handlerDefinition->options, 'timeout', 0.0, 'Fleep hook'),
            $this->floatOption($handlerDefinition->options, 'writing_timeout', 10.0, 'Fleep hook'),
            $this->nullableFloatOption($handlerDefinition->options, 'connection_timeout', 'Fleep hook'),
            $this->nullableIntOption($handlerDefinition->options, 'chunk_size', 'Fleep hook'),
        );
    }
}
