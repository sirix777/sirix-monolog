<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\DeduplicationHandler;
use Monolog\Level;
use Sirix\Monolog\FactoryInterface;
use Sirix\Monolog\HandlerManagerAwareInterface;
use Sirix\Monolog\HandlerManagerTrait;

class DeduplicationHandlerFactory implements FactoryInterface, HandlerManagerAwareInterface
{
    use HandlerManagerTrait;

    public function __invoke(array $options): DeduplicationHandler
    {
        $handler = $this->getHandlerManager()->get($options['handler']);
        $deduplicationStore = $options['deduplicationStore'] ?? null;
        $deduplicationLevel = $options['deduplicationLevel'] ?? Level::Debug;
        $time = (int) ($options['time'] ?? 60);
        $bubble = (bool) ($options['bubble'] ?? true);

        return new DeduplicationHandler(
            $handler,
            $deduplicationStore,
            $deduplicationLevel,
            $time,
            $bubble
        );
    }
}
