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

    public function create(ContainerInterface $container, HandlerDefinition $handlerDefinition): HandlerInterface
    {
        $configReader = ConfigReader::fromArray($handlerDefinition->options, self::class);

        return $this->newHandler(AmqpHandler::class, [
            $this->serviceObject($container, $handlerDefinition->options['exchange'] ?? null, 'exchange', 'AMQP', [
                'AMQPExchange',
                'PhpAmqpLib\Channel\AMQPChannel',
            ]),
            $configReader->optionalString('exchange_name'),
            $configReader->enum('level', Level::class, Level::Debug),
            $configReader->bool('bubble', true),
        ]);
    }
}
