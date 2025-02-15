<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\AmqpHandler;
use Monolog\Level;
use PhpAmqpLib\Channel\AMQPChannel;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Sirix\Monolog\ContainerAwareInterface;
use Sirix\Monolog\FactoryInterface;
use Sirix\Monolog\ServiceTrait;

class AmqpHandlerFactory implements FactoryInterface, ContainerAwareInterface
{
    use ServiceTrait;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(array $options): AmqpHandler
    {
        /** @var AMQPChannel $exchange */
        $exchange = $this->getService($options['exchange'] ?? null);
        $exchangeName = (string) ($options['exchangeName'] ?? 'log');
        $level = $options['level'] ?? Level::Debug;
        $bubble = (bool) ($options['bubble'] ?? true);

        return new AmqpHandler(
            $exchange,
            $exchangeName,
            $level,
            $bubble
        );
    }
}
