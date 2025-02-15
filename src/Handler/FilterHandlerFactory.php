<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\FilterHandler;
use Monolog\Level;
use Sirix\Monolog\FactoryInterface;
use Sirix\Monolog\HandlerManagerAwareInterface;
use Sirix\Monolog\HandlerManagerTrait;

class FilterHandlerFactory implements FactoryInterface, HandlerManagerAwareInterface
{
    use HandlerManagerTrait;

    public function __invoke(array $options): FilterHandler
    {
        $handler = $this->getHandlerManager()->get($options['handler']);
        $minLevelOrList = $options['minLevelOrList'] ?? Level::Debug;
        $maxLevel = $options['maxLevel'] ?? Level::Emergency;
        $bubble = (bool) ($options['bubble'] ?? true);

        return new FilterHandler(
            $handler,
            $minLevelOrList,
            $maxLevel,
            $bubble
        );
    }
}
