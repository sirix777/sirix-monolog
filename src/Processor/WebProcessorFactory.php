<?php

declare(strict_types=1);

namespace Sirix\Monolog\Processor;

use ArrayAccess;
use Monolog\Processor\WebProcessor;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Sirix\Monolog\ContainerAwareInterface;
use Sirix\Monolog\ContainerTrait;
use Sirix\Monolog\Exception\MissingServiceException;
use Sirix\Monolog\FactoryInterface;

use function is_array;

class WebProcessorFactory implements FactoryInterface, ContainerAwareInterface
{
    use ContainerTrait;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(array $options): WebProcessor
    {
        $serverData = $this->getServerDataService($options);
        $extraFields = $options['extraFields'] ?? null;

        return new WebProcessor(
            $serverData,
            $extraFields
        );
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getServerDataService(array $options): mixed
    {
        if (empty($options['serverData'])) {
            return null;
        }

        if (
            is_array($options['serverData'])
            || $options['serverData'] instanceof ArrayAccess
        ) {
            return $options['serverData'];
        }

        if (! $this->getContainer()->has($options['serverData'])) {
            throw new MissingServiceException(
                'No serverData service found'
            );
        }

        return $this->getContainer()->get($options['serverData']);
    }
}
