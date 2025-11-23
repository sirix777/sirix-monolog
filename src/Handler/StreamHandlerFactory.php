<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Sirix\Monolog\ContainerAwareInterface;
use Sirix\Monolog\ContainerTrait;
use Sirix\Monolog\FactoryInterface;

use function is_resource;
use function is_string;

class StreamHandlerFactory implements FactoryInterface, ContainerAwareInterface
{
    use ContainerTrait;

    protected ContainerInterface $container;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(array $options): StreamHandler
    {
        $stream = $this->getStream($options['stream'] ?? null);

        $level = $options['level'] ?? Level::Debug;
        $bubble = (bool) ($options['bubble'] ?? true);
        $filePermission = (int) ($options['filePermission'] ?? 0o644);
        $useLocking = (bool) ($options['useLocking'] ?? true);

        return new StreamHandler($stream, $level, $bubble, $filePermission, $useLocking);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function getStream(mixed $stream): mixed
    {
        if (is_string($stream) && $this->container->has($stream)) {
            return $this->container->get($stream);
        }

        if (is_resource($stream)) {
            return $stream;
        }

        return (string) $stream;
    }
}
