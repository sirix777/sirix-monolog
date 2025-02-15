<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\DoctrineCouchDBHandler;
use Monolog\Level;
use Sirix\Monolog\ContainerAwareInterface;
use Sirix\Monolog\FactoryInterface;
use Sirix\Monolog\ServiceTrait;

class DoctrineCouchDBHandlerFactory implements FactoryInterface, ContainerAwareInterface
{
    use ServiceTrait;

    public function __invoke(array $options): DoctrineCouchDBHandler
    {
        $client = $this->getService($options['client'] ?? null);
        $level = $options['level'] ?? Level::Debug;
        $bubble = (bool) ($options['bubble'] ?? true);

        return new DoctrineCouchDBHandler(
            $client,
            $level,
            $bubble
        );
    }
}
