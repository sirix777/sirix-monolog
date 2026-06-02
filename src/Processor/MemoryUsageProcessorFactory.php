<?php

declare(strict_types=1);

namespace Sirix\Monolog\Processor;

use Monolog\Processor\MemoryUsageProcessor;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\ProcessorDefinition;

class MemoryUsageProcessorFactory implements ProcessorFactoryInterface
{
    public function create(ContainerInterface $container, ProcessorDefinition $processorDefinition): MemoryUsageProcessor
    {
        $configReader = ConfigReader::fromArray($processorDefinition->options, self::class);

        return new MemoryUsageProcessor(
            $configReader->bool('real_usage', true),
            $configReader->bool('use_formatting', true),
        );
    }
}
