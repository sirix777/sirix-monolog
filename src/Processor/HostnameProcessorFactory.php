<?php

declare(strict_types=1);

namespace Sirix\Monolog\Processor;

use Monolog\Processor\HostnameProcessor;
use Psr\Container\ContainerInterface;
use Sirix\Monolog\Config\ProcessorDefinition;

class HostnameProcessorFactory implements ProcessorFactoryInterface
{
    public function create(ContainerInterface $container, ProcessorDefinition $definition): HostnameProcessor
    {
        return new HostnameProcessor();
    }
}
