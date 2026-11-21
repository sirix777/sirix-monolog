<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Support;

use Monolog\Handler\HandlerInterface;
use Monolog\Handler\NoopHandler;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;
use Sirix\Monolog\Handler\HandlerFactoryInterface;

final class InvalidOptionHandlerFactory implements HandlerFactoryInterface
{
    public function create(ContainerInterface $container, HandlerDefinition $handlerDefinition): HandlerInterface
    {
        ConfigReader::fromArray($handlerDefinition->options, self::class)->requiredInt('size');

        return new NoopHandler();
    }
}
