<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\ElasticaHandler;
use Monolog\Level;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Sirix\Monolog\ContainerAwareInterface;
use Sirix\Monolog\FactoryInterface;
use Sirix\Monolog\ServiceTrait;

class ElasticaHandlerFactory implements FactoryInterface, ContainerAwareInterface
{
    use ServiceTrait;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(array $options): ElasticaHandler
    {
        $client = $this->getService($options['client'] ?? null);
        $index = (string) ($options['index'] ?? 'monolog');
        $type = (string) ($options['type'] ?? 'record');
        $ignoreError = (bool) ($options['ignoreError'] ?? false);
        $level = $options['level'] ?? Level::Debug;
        $bubble = (bool) ($options['bubble'] ?? true);

        return new ElasticaHandler(
            $client,
            [
                'index' => $index,
                'type' => $type,
                'ignore_error' => $ignoreError,
            ],
            $level,
            $bubble
        );
    }
}
