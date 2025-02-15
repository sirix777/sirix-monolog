<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\RedisHandler;
use Monolog\Level;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Sirix\Monolog\ContainerAwareInterface;
use Sirix\Monolog\FactoryInterface;
use Sirix\Monolog\ServiceTrait;

class RedisHandlerFactory implements FactoryInterface, ContainerAwareInterface
{
    use ServiceTrait;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(array $options): RedisHandler
    {
        $client = $this->getService($options['client'] ?? []);
        $key = (string) ($options['key'] ?? '');
        $level = $options['level'] ?? Level::Debug;
        $bubble = (bool) ($options['bubble'] ?? true);
        $capSize = (int) ($options['capSize'] ?? 0);

        return new RedisHandler(
            $client,
            $key,
            $level,
            $bubble,
            $capSize
        );
    }
}
