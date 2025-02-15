<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\PsrHandler;
use Monolog\Level;
use Sirix\Monolog\ContainerAwareInterface;
use Sirix\Monolog\FactoryInterface;
use Sirix\Monolog\ServiceTrait;

class PsrHandlerFactory implements FactoryInterface, ContainerAwareInterface
{
    use ServiceTrait;

    public function __invoke(array $options): PsrHandler
    {
        $logger = $this->getService($options['logger'] ?? null);
        $level = $options['level'] ?? Level::Debug;
        $bubble = (bool) ($options['bubble'] ?? true);

        return new PsrHandler(
            $logger,
            $level,
            $bubble
        );
    }
}
