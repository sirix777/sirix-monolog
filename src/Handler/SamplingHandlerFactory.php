<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\SamplingHandler;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Sirix\Monolog\Exception\InvalidConfigException;
use Sirix\Monolog\FactoryInterface;
use Sirix\Monolog\HandlerManagerAwareInterface;
use Sirix\Monolog\HandlerManagerTrait;

class SamplingHandlerFactory implements FactoryInterface, HandlerManagerAwareInterface
{
    use HandlerManagerTrait;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(array $options): SamplingHandler
    {
        $handler = $this->getHandlerManager()->get($options['handler']);
        $factor = (int) ($options['factor'] ?? null);

        if (0 === $factor) {
            throw new InvalidConfigException(
                'Factor is missing or is less then 1'
            );
        }

        return new SamplingHandler(
            $handler,
            $factor
        );
    }
}
