<?php

declare(strict_types=1);

namespace Sirix\Monolog\Processor;

use Monolog\Level;
use Monolog\Processor\IntrospectionProcessor;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\ProcessorDefinition;

class IntrospectionProcessorFactory implements ProcessorFactoryInterface
{
    public function create(ContainerInterface $container, ProcessorDefinition $processorDefinition): IntrospectionProcessor
    {
        $configReader = ConfigReader::fromArray($processorDefinition->options, self::class);
        $level = $configReader->enum('level', Level::class, Level::Debug);

        return new IntrospectionProcessor(
            $level,
            $configReader->stringList('skip_classes_partials', []),
            $configReader->int('skip_stack_frames_count', 0),
        );
    }
}
