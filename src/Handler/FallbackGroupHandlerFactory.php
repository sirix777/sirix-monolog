<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\FallbackGroupHandler;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class FallbackGroupHandlerFactory
{
    use GetHandlersTrait;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(array $options): FallbackGroupHandler
    {
        $handlers = $this->getHandlers($options);
        $bubble = (bool) ($options['bubble'] ?? true);

        return new FallbackGroupHandler(
            $handlers,
            $bubble
        );
    }
}
