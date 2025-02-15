<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\MongoDBHandler;
use Monolog\Level;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Sirix\Monolog\ContainerAwareInterface;
use Sirix\Monolog\FactoryInterface;
use Sirix\Monolog\ServiceTrait;

class MongoDBHandlerFactory implements FactoryInterface, ContainerAwareInterface
{
    use ServiceTrait;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(array $options): MongoDBHandler
    {
        $client = $this->getService($options['client'] ?? null);
        $database = (string) ($options['database'] ?? '');
        $collection = (string) ($options['collection'] ?? '');
        $level = $options['level'] ?? Level::Debug;
        $bubble = (bool) ($options['bubble'] ?? true);

        return new MongoDBHandler(
            $client,
            $database,
            $collection,
            $level,
            $bubble
        );
    }
}
