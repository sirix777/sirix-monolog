<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\HandlerInterface;
use Monolog\Handler\RedisHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;

class RedisHandlerFactory implements HandlerFactoryInterface
{
    use ReflectiveHandlerFactoryTrait;

    public function create(ContainerInterface $container, HandlerDefinition $definition): HandlerInterface
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        return $this->newHandler(RedisHandler::class, [
            $this->serviceObject($container, $definition->options['redis'] ?? null, 'redis', 'Redis', ['Predis\Client', 'Redis']),
            $options->requiredNonEmptyString('key'),
            $options->enum('level', Level::class, Level::Debug),
            $options->bool('bubble', true),
            $options->int('cap_size', 0),
        ]);
    }
}
