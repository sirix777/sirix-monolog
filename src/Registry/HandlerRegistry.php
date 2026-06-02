<?php

declare(strict_types=1);

namespace Sirix\Monolog\Registry;

use Monolog\Handler\HandlerInterface;
use Sirix\Monolog\Builder\HandlerBuilder;
use Sirix\Monolog\Exception\InvalidConfigException;

final class HandlerRegistry
{
    /** @var array<string, HandlerInterface> */
    private array $handlers = [];

    /** @var array<string, true> */
    private array $building = [];

    public function __construct(private readonly HandlerBuilder $handlerBuilder)
    {
        $this->handlerBuilder->setHandlerRegistry($this);
    }

    public function get(string $handlerId): HandlerInterface
    {
        if (isset($this->handlers[$handlerId])) {
            return $this->handlers[$handlerId];
        }

        if (isset($this->building[$handlerId])) {
            throw new InvalidConfigException("Circular monolog handler reference detected for '{$handlerId}'.");
        }

        $this->building[$handlerId] = true;

        try {
            return $this->handlers[$handlerId] = $this->handlerBuilder->build($handlerId);
        } finally {
            unset($this->building[$handlerId]);
        }
    }
}
