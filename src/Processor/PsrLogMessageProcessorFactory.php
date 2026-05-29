<?php

declare(strict_types=1);

namespace Sirix\Monolog\Processor;

use Monolog\Processor\PsrLogMessageProcessor;
use Psr\Container\ContainerInterface;
use Sirix\Monolog\Config\ProcessorDefinition;

class PsrLogMessageProcessorFactory implements ProcessorFactoryInterface
{
    public function create(ContainerInterface $container, ProcessorDefinition $definition): PsrLogMessageProcessor
    {
        return new PsrLogMessageProcessor();
    }
}
