<?php

declare(strict_types=1);

namespace Sirix\Monolog\Processor;

use Monolog\Processor\TagProcessor;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\ProcessorDefinition;

class TagProcessorFactory implements ProcessorFactoryInterface
{
    public function create(ContainerInterface $container, ProcessorDefinition $processorDefinition): TagProcessor
    {
        $configReader = ConfigReader::fromArray($processorDefinition->options, self::class);

        return new TagProcessor($configReader->stringList('tags', []));
    }
}
