<?php

declare(strict_types=1);

namespace Sirix\Monolog\Processor;

use Monolog\Processor\MemoryPeakUsageProcessor;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\ProcessorDefinition;

class MemoryPeakUsageProcessorFactory implements ProcessorFactoryInterface
{
    public function create(ContainerInterface $container, ProcessorDefinition $processorDefinition): MemoryPeakUsageProcessor
    {
        $configReader = ConfigReader::fromArray($processorDefinition->options, self::class);

        return new MemoryPeakUsageProcessor(
            $configReader->bool('real_usage', true),
            $configReader->bool('use_formatting', true),
        );
    }
}
