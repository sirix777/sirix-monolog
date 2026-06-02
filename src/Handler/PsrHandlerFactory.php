<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\PsrHandler;
use Monolog\Level;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\ContainerResolver\ContainerResolver;
use Sirix\Monolog\Config\HandlerDefinition;

class PsrHandlerFactory implements HandlerFactoryInterface
{
    /**
     * @throws ContainerExceptionInterface
     */
    public function create(ContainerInterface $container, HandlerDefinition $handlerDefinition): PsrHandler
    {
        $configReader = ConfigReader::fromArray($handlerDefinition->options, self::class);
        $level = $configReader->enum('level', Level::class, Level::Debug);
        $loggerId = $configReader->requiredNonEmptyString('logger');

        return new PsrHandler(
            ContainerResolver::forContext($container, self::class)->getAs($loggerId, LoggerInterface::class),
            $level,
            $configReader->bool('bubble', true),
            $configReader->bool('include_extra', false),
        );
    }
}
