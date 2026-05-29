<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Sirix\Monolog\Exception\MissingServiceException;
use Sirix\Monolog\Registry\HandlerRegistry;

trait HandlerRegistryTrait
{
    private ?HandlerRegistry $handlerRegistry = null;

    public function setHandlerRegistry(HandlerRegistry $handlerRegistry): void
    {
        $this->handlerRegistry = $handlerRegistry;
    }

    protected function getHandlerRegistry(): HandlerRegistry
    {
        if (! $this->handlerRegistry instanceof HandlerRegistry) {
            throw new MissingServiceException('Unable to get HandlerRegistry.');
        }

        return $this->handlerRegistry;
    }
}
