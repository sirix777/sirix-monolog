<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\GelfHandler;
use Monolog\Level;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Sirix\Monolog\ContainerAwareInterface;
use Sirix\Monolog\FactoryInterface;
use Sirix\Monolog\ServiceTrait;

class GelfHandlerFactory implements FactoryInterface, ContainerAwareInterface
{
    use ServiceTrait;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(array $options): GelfHandler
    {
        $publisher = $this->getService($options['publisher'] ?? null);
        $level = $options['level'] ?? Level::Debug;
        $bubble = (bool) ($options['bubble'] ?? true);

        return new GelfHandler(
            $publisher,
            $level,
            $bubble
        );
    }
}
