<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\GroupHandler;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Sirix\Monolog\FactoryInterface;
use Sirix\Monolog\HandlerManagerAwareInterface;

class GroupHandlerFactory implements FactoryInterface, HandlerManagerAwareInterface
{
    use GetHandlersTrait;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(array $options): GroupHandler
    {
        $handlers = $this->getHandlers($options);
        $bubble = (bool) ($options['bubble'] ?? true);

        return new GroupHandler(
            $handlers,
            $bubble
        );
    }
}
