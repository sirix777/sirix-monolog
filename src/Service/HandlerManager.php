<?php

declare(strict_types=1);

namespace Sirix\Monolog\Service;

use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\HandlerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Sirix\Monolog\Config\HandlerConfig;
use Sirix\Monolog\ConfigInterface;
use Sirix\Monolog\Exception\MissingServiceException;
use Sirix\Monolog\Exception\UnknownServiceException;

use function array_key_exists;
use function method_exists;

class HandlerManager extends AbstractServiceManager
{
    protected ?FormatterManager $formatterManager = null;
    protected ?ProcessorManager $processorManager = null;

    public function getServiceConfig(string $id): ?ConfigInterface
    {
        return $this->config->getHandlerConfig($id);
    }

    public function hasServiceConfig(string $id): bool
    {
        return $this->config->hasHandlerConfig($id);
    }

    public function get(string $id): HandlerInterface
    {
        if (array_key_exists($id, $this->services)) {
            return $this->services[$id];
        }

        /** @var HandlerInterface $handler */
        $handler = parent::get($id);

        $config = $this->config->getHandlerConfig($id);

        if (! $config instanceof HandlerConfig) {
            return $handler;
        }

        $formatter = $config->getFormatter();

        if ($formatter && method_exists($handler, 'setFormatter')) {
            $handler->setFormatter($this->getFormatter($formatter));
        }

        $processors = $config->getProcessors();

        if (! method_exists($handler, 'pushProcessor')) {
            return $handler;
        }

        foreach ($processors as $processorName) {
            $handler->pushProcessor($this->getProcessorManager()->get($processorName));
        }

        return $handler;
    }

    public function setFormatterManager(FormatterManager $manager): void
    {
        $this->formatterManager = $manager;
    }

    public function getFormatterManager(): FormatterManager
    {
        if (! $this->formatterManager instanceof FormatterManager) {
            throw new MissingServiceException(
                'Unable to get FormatterManager.'
            );
        }

        return $this->formatterManager;
    }

    public function setProcessorManager(ProcessorManager $processorManager): void
    {
        $this->processorManager = $processorManager;
    }

    public function getProcessorManager(): ProcessorManager
    {
        if (! $this->processorManager instanceof ProcessorManager) {
            throw new MissingServiceException(
                'Unable to get ProcessorManager.'
            );
        }

        return $this->processorManager;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function getFormatter(string $id): FormatterInterface
    {
        if (! $this->getFormatterManager()->has($id)) {
            throw new UnknownServiceException(
                "Unable to locate formatter {$id}."
            );
        }

        return $this->getFormatterManager()->get($id);
    }
}
