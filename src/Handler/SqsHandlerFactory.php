<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\SqsHandler;
use Monolog\Level;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Sirix\Monolog\ContainerAwareInterface;
use Sirix\Monolog\FactoryInterface;
use Sirix\Monolog\ServiceTrait;

class SqsHandlerFactory implements FactoryInterface, ContainerAwareInterface
{
    use ServiceTrait;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(array $options): SqsHandler
    {
        $sqsClient = $this->getService($options['sqsClient'] ?? null);

        $queueUrl = (string) $options['queueUrl'];
        $level = $options['level'] ?? Level::Debug;
        $bubble = (bool) ($options['bubble'] ?? true);

        return new SqsHandler($sqsClient, $queueUrl, $level, $bubble);
    }
}
