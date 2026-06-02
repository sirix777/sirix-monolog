<?php

declare(strict_types=1);

namespace Sirix\Monolog\Processor;

use Monolog\Processor\ClosureContextProcessor;
use Psr\Container\ContainerInterface;
use Sirix\Monolog\Config\ProcessorDefinition;

class ClosureContextProcessorFactory implements ProcessorFactoryInterface
{
    public function create(ContainerInterface $container, ProcessorDefinition $processorDefinition): ClosureContextProcessor
    {
        return new ClosureContextProcessor();
    }
}
