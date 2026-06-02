<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\LogglyHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;

class LogglyHandlerFactory implements HandlerFactoryInterface
{
    use HandlerOptionTrait;

    public function create(ContainerInterface $container, HandlerDefinition $handlerDefinition): LogglyHandler
    {
        $configReader = ConfigReader::fromArray($handlerDefinition->options, self::class);

        $logglyHandler = new LogglyHandler(
            $configReader->requiredNonEmptyString('token'),
            $configReader->enum('level', Level::class, Level::Debug),
            $configReader->bool('bubble', true),
        );

        if ($configReader->has('tag')) {
            $logglyHandler->setTag($this->stringOrStringListOption($handlerDefinition->options['tag'], 'tag', 'Loggly'));
        }

        return $logglyHandler;
    }
}
