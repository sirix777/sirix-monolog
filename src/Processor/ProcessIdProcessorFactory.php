<?php

declare(strict_types=1);

namespace Sirix\Monolog\Processor;

use Monolog\Processor\ProcessIdProcessor;
use Psr\Container\ContainerInterface;
use Sirix\Monolog\Config\ProcessorDefinition;

class ProcessIdProcessorFactory implements ProcessorFactoryInterface
{
    public function create(ContainerInterface $container, ProcessorDefinition $processorDefinition): ProcessIdProcessor
    {
        return new ProcessIdProcessor();
    }
}
