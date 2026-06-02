<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Sirix\Monolog\Registry\HandlerRegistry;

interface HandlerRegistryAwareInterface
{
    public function setHandlerRegistry(HandlerRegistry $handlerRegistry): void;
}
