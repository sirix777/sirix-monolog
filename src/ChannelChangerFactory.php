<?php

declare(strict_types=1);

namespace Sirix\Monolog;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Sirix\Monolog\Config\MainConfig;
use Sirix\Monolog\Formatter\FormatterMapper;
use Sirix\Monolog\Handler\HandlerMapper;
use Sirix\Monolog\Processor\ProcessorMapper;
use Sirix\Monolog\Service\FormatterManager;
use Sirix\Monolog\Service\HandlerManager;
use Sirix\Monolog\Service\ProcessorManager;

class ChannelChangerFactory
{
    protected ?array $config = null;

    protected ?HandlerManager $handlerManager = null;

    protected ?ProcessorManager $processManager = null;

    protected ?FormatterManager $formatterManager = null;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ChannelChanger
    {
        $config = $this->getMainConfig($container);
        $handlerManager = $this->getHandlerManager($container);
        $processorManager = $this->getProcessorManager($container);

        return new ChannelChanger(
            $config,
            $handlerManager,
            $processorManager
        );
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getMainConfig(ContainerInterface $container): MainConfig
    {
        $config = $this->getConfigArray($container);

        return new MainConfig($config);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getHandlerManager(ContainerInterface $container): ?HandlerManager
    {
        $config = $this->getMainConfig($container);
        $this->handlerManager = new HandlerManager(
            $config,
            new HandlerMapper(),
            $container
        );

        $this->handlerManager->setFormatterManager($this->getFormatterManager($container));
        $this->handlerManager->setProcessorManager($this->getProcessorManager($container));

        return $this->handlerManager;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getFormatterManager(ContainerInterface $container): FormatterManager
    {
        $config = $this->getMainConfig($container);
        $this->formatterManager = new FormatterManager(
            $config,
            new FormatterMapper(),
            $container
        );

        return $this->formatterManager;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getProcessorManager(ContainerInterface $container): ProcessorManager
    {
        $config = $this->getMainConfig($container);
        $this->processManager = new ProcessorManager(
            $config,
            new ProcessorMapper(),
            $container
        );

        return $this->processManager;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function getConfigArray(ContainerInterface $container): array
    {
        if ($container->has('config')) {
            return $container->get('config');
        }

        return [];
    }
}
