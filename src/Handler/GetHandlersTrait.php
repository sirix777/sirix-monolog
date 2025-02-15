<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Sirix\Monolog\Exception\MissingConfigException;
use Sirix\Monolog\HandlerManagerTrait;

use function is_array;

trait GetHandlersTrait
{
    use HandlerManagerTrait;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function getHandlers(array $options): array
    {
        $handlers = $options['handlers'] ?? [];

        if (empty($handlers) || ! is_array($handlers)) {
            throw new MissingConfigException(
                'No handlers specified'
            );
        }

        $return = [];

        foreach ($handlers as $handler) {
            $return[] = $this->getHandlerManager()->get($handler);
        }

        return $return;
    }
}
