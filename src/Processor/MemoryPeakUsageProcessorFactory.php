<?php

declare(strict_types=1);

namespace Sirix\Monolog\Processor;

use Monolog\Processor\MemoryPeakUsageProcessor;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\ProcessorDefinition;

class MemoryPeakUsageProcessorFactory implements ProcessorFactoryInterface
{
    public function create(ContainerInterface $container, ProcessorDefinition $definition): MemoryPeakUsageProcessor
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        return new MemoryPeakUsageProcessor(
            $options->bool('real_usage', true),
            $options->bool('use_formatting', true),
        );
    }
}
