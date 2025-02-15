<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\WhatFailureGroupHandler;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class WhatFailureGroupHandlerFactory
{
    use GetHandlersTrait;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(array $options): WhatFailureGroupHandler
    {
        $handlers = $this->getHandlers($options);
        $bubble = (bool) ($options['bubble'] ?? true);

        return new WhatFailureGroupHandler(
            $handlers,
            $bubble
        );
    }
}
