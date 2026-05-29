<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\HandlerInterface;
use Monolog\Handler\RedisPubSubHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;

class RedisPubSubHandlerFactory implements HandlerFactoryInterface
{
    use ReflectiveHandlerFactoryTrait;

    public function create(ContainerInterface $container, HandlerDefinition $definition): HandlerInterface
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        return $this->newHandler(RedisPubSubHandler::class, [
            $this->serviceObject($container, $definition->options['redis'] ?? null, 'redis', 'Redis pub/sub', ['Predis\Client', 'Redis']),
            $options->requiredNonEmptyString('key'),
            $options->enum('level', Level::class, Level::Debug),
            $options->bool('bubble', true),
        ]);
    }
}
