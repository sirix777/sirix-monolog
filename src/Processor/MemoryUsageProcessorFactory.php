<?php

declare(strict_types=1);

namespace Sirix\Monolog\Processor;

use Monolog\Processor\MemoryUsageProcessor;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\ProcessorDefinition;

class MemoryUsageProcessorFactory implements ProcessorFactoryInterface
{
    public function create(ContainerInterface $container, ProcessorDefinition $definition): MemoryUsageProcessor
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        return new MemoryUsageProcessor(
            $options->bool('real_usage', true),
            $options->bool('use_formatting', true),
        );
    }
}
