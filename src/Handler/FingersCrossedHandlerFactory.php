<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\FingersCrossedHandler;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Sirix\Monolog\ContainerAwareInterface;
use Sirix\Monolog\ContainerTrait;
use Sirix\Monolog\FactoryInterface;
use Sirix\Monolog\HandlerManagerAwareInterface;
use Sirix\Monolog\HandlerManagerTrait;

use function is_string;

class FingersCrossedHandlerFactory implements FactoryInterface, HandlerManagerAwareInterface, ContainerAwareInterface
{
    use ContainerTrait;
    use HandlerManagerTrait;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(array $options): FingersCrossedHandler
    {
        $handler = $this->getHandlerManager()->get($options['handler']);
        $activationStrategy = $this->getActivationStrategy($options);
        $bufferSize = (int) ($options['bufferSize'] ?? 0);
        $bubble = (bool) ($options['bubble'] ?? true);
        $stopBuffering = (bool) ($options['stopBuffering'] ?? true);
        $passthruLevel = $options['passthruLevel'] ?? null;

        return new FingersCrossedHandler(
            $handler,
            $activationStrategy,
            $bufferSize,
            $bubble,
            $stopBuffering,
            $passthruLevel
        );
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function getActivationStrategy(array $options): mixed
    {
        $activationStrategy = $options['activationStrategy'] ?? null;

        if (! $activationStrategy) {
            return null;
        }

        if (is_string($activationStrategy) && $this->getContainer()->has($activationStrategy)) {
            return $this->getContainer()->get($activationStrategy);
        }

        return $activationStrategy;
    }
}
