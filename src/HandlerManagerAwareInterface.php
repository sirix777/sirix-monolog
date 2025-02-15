<?php

declare(strict_types=1);

namespace Sirix\Monolog;

use Sirix\Monolog\Service\HandlerManager;

interface HandlerManagerAwareInterface
{
    public function getHandlerManager(): HandlerManager;

    public function setHandlerManager(HandlerManager $container): void;
}
