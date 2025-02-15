<?php

declare(strict_types=1);

namespace Sirix\Monolog;

use Sirix\Monolog\Exception\MissingServiceException;
use Sirix\Monolog\Service\HandlerManager;

trait HandlerManagerTrait
{
    protected ?HandlerManager $handlerManager = null;

    public function getHandlerManager(): HandlerManager
    {
        if (null === $this->handlerManager) {
            throw new MissingServiceException('Handler Manager service not set');
        }

        return $this->handlerManager;
    }

    public function setHandlerManager(HandlerManager $handlerManager): void
    {
        $this->handlerManager = $handlerManager;
    }
}
