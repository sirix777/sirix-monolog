<?php

declare(strict_types=1);

namespace Sirix\Monolog;

use Monolog\Handler\HandlerInterface;
use Monolog\Logger;
use Monolog\Processor\ProcessorInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;
use Sirix\Monolog\Config\ChannelConfig;
use Sirix\Monolog\Config\MainConfig;
use Sirix\Monolog\Exception\MissingConfigException;
use Sirix\Monolog\Exception\UnknownServiceException;
use Sirix\Monolog\Service\HandlerManager;
use Sirix\Monolog\Service\ProcessorManager;

class ChannelChanger implements ContainerInterface
{
    /** @var LoggerInterface[] */
    protected array $channels = [];

    public function __construct(
        protected MainConfig $config,
        protected ?HandlerManager $handlerManager,
        protected ?ProcessorManager $processorManager
    ) {}

    public function get(string $id): LoggerInterface
    {
        if (! empty($this->channels[$id])) {
            return $this->channels[$id];
        }

        if (! $this->has($id)) {
            throw new MissingConfigException(
                "Unable to locate channel {$id}."
            );
        }

        $config = $this->config->getChannelConfig($id);

        if (! $config instanceof ChannelConfig) {
            throw new MissingConfigException(
                'Unable to locate channel config'
            );
        }

        $name = $config->getName() ?? $id;

        $channel = new Logger($name);

        $handlersToUse = $config->getHandlers();

        foreach ($handlersToUse as $handlerToUse) {
            $handler = $this->getHandler($handlerToUse);
            $channel->pushHandler($handler);
        }

        $processorsToUse = $config->getProcessors();

        foreach ($processorsToUse as $processorToUse) {
            $processor = $this->getProcessor($processorToUse);
            $channel->pushProcessor($processor);
        }

        $this->channels[$id] = $channel;

        return $this->channels[$id];
    }

    public function has(string $id): bool
    {
        return $this->config->hasChannelConfig($id);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function getHandler(string $id): HandlerInterface
    {
        if (null === $this->handlerManager) {
            throw new UnknownServiceException(
                'Processor manager is not configured.'
            );
        }

        if (! $this->handlerManager->has($id)) {
            throw new UnknownServiceException(
                "Unable to locate processor {$id}."
            );
        }

        return $this->handlerManager->get($id);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function getProcessor(string $id): ProcessorInterface
    {
        if (null === $this->processorManager) {
            throw new UnknownServiceException(
                'Processor manager is not configured.'
            );
        }

        if (! $this->processorManager->has($id)) {
            throw new UnknownServiceException(
                "Unable to locate processor {$id}."
            );
        }

        return $this->processorManager->get($id);
    }
}
