<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\PsrHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\ContainerResolver\ContainerResolver;
use Sirix\Monolog\Config\HandlerDefinition;

class PsrHandlerFactory implements HandlerFactoryInterface
{
    public function create(ContainerInterface $container, HandlerDefinition $definition): PsrHandler
    {
        $options = ConfigReader::fromArray($definition->options, self::class);
        $level = $options->enum('level', Level::class, Level::Debug);
        $loggerId = $options->requiredNonEmptyString('logger');

        return new PsrHandler(
            ContainerResolver::forContext($container, self::class)->getAs($loggerId, LoggerInterface::class),
            $level,
            $options->bool('bubble', true),
            $options->bool('include_extra', false),
        );
    }
}
