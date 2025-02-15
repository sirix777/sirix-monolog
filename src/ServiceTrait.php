<?php

declare(strict_types=1);

namespace Sirix\Monolog;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Sirix\Monolog\Exception\MissingConfigException;
use Sirix\Monolog\Exception\MissingServiceException;

trait ServiceTrait
{
    use ContainerTrait;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getService(string $name): mixed
    {
        if ('' === $name || '0' === $name) {
            throw new MissingConfigException(
                'No service name found in config'
            );
        }

        if (! $this->getContainer()->has($name)) {
            throw new MissingServiceException(
                "No service found for : {$name}."
            );
        }

        return $this->getContainer()->get($name);
    }
}
