<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\DynamoDbHandler;
use Monolog\Level;
use Sirix\Monolog\ContainerAwareInterface;
use Sirix\Monolog\FactoryInterface;
use Sirix\Monolog\ServiceTrait;

class DynamoDbHandlerFactory implements FactoryInterface, ContainerAwareInterface
{
    use ServiceTrait;

    public function __invoke(array $options): DynamoDbHandler
    {
        $client = $this->getService($options['client'] ?? null);
        $table = (string) ($options['table'] ?? null);
        $level = $options['level'] ?? Level::Debug;
        $bubble = (bool) ($options['bubble'] ?? true);

        return new DynamoDbHandler(
            $client,
            $table,
            $level,
            $bubble
        );
    }
}
