<?php

declare(strict_types=1);

namespace Sirix\Monolog\Processor;

use Monolog\Processor\TagProcessor;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\ProcessorDefinition;

class TagProcessorFactory implements ProcessorFactoryInterface
{
    public function create(ContainerInterface $container, ProcessorDefinition $definition): TagProcessor
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        return new TagProcessor($options->stringList('tags', []));
    }
}
