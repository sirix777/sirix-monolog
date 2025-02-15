<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\OverflowHandler;
use Monolog\Level;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Sirix\Monolog\FactoryInterface;
use Sirix\Monolog\HandlerManagerAwareInterface;
use Sirix\Monolog\HandlerManagerTrait;

class OverflowHandlerFactory implements FactoryInterface, HandlerManagerAwareInterface
{
    use HandlerManagerTrait;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(array $options): OverflowHandler
    {
        $handler = $this->getHandlerManager()->get($options['handler']);
        $thresholdMap = [
            Level::Debug->value => $options['thresholdMap']['debug'] ?? 0,
            Level::Info->value => $options['thresholdMap']['info'] ?? 0,
            Level::Notice->value => $options['thresholdMap']['notice'] ?? 0,
            Level::Warning->value => $options['thresholdMap']['warning'] ?? 0,
            Level::Error->value => $options['thresholdMap']['error'] ?? 0,
            Level::Critical->value => $options['thresholdMap']['critical'] ?? 0,
            Level::Alert->value => $options['thresholdMap']['alert'] ?? 0,
            Level::Emergency->value => $options['thresholdMap']['emergency'] ?? 0,
        ];

        $level = $options['level'] ?? Level::Debug;
        $bubble = (bool) ($options['bubble'] ?? true);

        return new OverflowHandler(
            $handler,
            $thresholdMap,
            $level,
            $bubble
        );
    }
}
