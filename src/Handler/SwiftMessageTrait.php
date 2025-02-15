<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Sirix\Monolog\Exception\MissingConfigException;
use Sirix\Monolog\Exception\MissingServiceException;
use Sirix\Monolog\ServiceTrait;
use Swift_Message;

use function is_callable;

trait SwiftMessageTrait
{
    use ServiceTrait;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getSwiftMessage(array $options): callable|Swift_Message
    {
        if (empty($options['message'])) {
            throw new MissingConfigException(
                'No message service name or callback provided'
            );
        }

        if (is_callable($options['message'])) {
            return $options['message'];
        }

        if (! $this->getContainer()->has($options['message'])) {
            throw new MissingServiceException(
                'No Message service found'
            );
        }

        return $this->getContainer()->get($options['message']);
    }
}
