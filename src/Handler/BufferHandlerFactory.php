<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\BufferHandler;
use Monolog\Level;
use Sirix\Monolog\FactoryInterface;
use Sirix\Monolog\HandlerManagerAwareInterface;
use Sirix\Monolog\HandlerManagerTrait;

class BufferHandlerFactory implements FactoryInterface, HandlerManagerAwareInterface
{
    use HandlerManagerTrait;

    public function __invoke(array $options): BufferHandler
    {
        $handler = $this->getHandlerManager()->get($options['handler']);
        $bufferLimit = (int) ($options['bufferLimit'] ?? 0);
        $level = $options['level'] ?? Level::Debug;
        $bubble = (bool) ($options['bubble'] ?? true);
        $flushOnOverflow = (bool) ($options['flushOnOverflow'] ?? true);

        return new BufferHandler(
            $handler,
            $bufferLimit,
            $level,
            $bubble,
            $flushOnOverflow
        );
    }
}
