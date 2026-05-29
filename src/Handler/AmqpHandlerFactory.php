<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\AmqpHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;

class AmqpHandlerFactory implements HandlerFactoryInterface
{
    use ReflectiveHandlerFactoryTrait;

    public function create(ContainerInterface $container, HandlerDefinition $definition): HandlerInterface
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        return $this->newHandler(AmqpHandler::class, [
            $this->serviceObject($container, $definition->options['exchange'] ?? null, 'exchange', 'AMQP', [
                'AMQPExchange',
                'PhpAmqpLib\Channel\AMQPChannel',
            ]),
            $options->optionalString('exchange_name'),
            $options->enum('level', Level::class, Level::Debug),
            $options->bool('bubble', true),
        ]);
    }
}
