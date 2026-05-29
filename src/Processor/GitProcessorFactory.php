<?php

declare(strict_types=1);

namespace Sirix\Monolog\Processor;

use Monolog\Level;
use Monolog\Processor\GitProcessor;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\ProcessorDefinition;

class GitProcessorFactory implements ProcessorFactoryInterface
{
    public function create(ContainerInterface $container, ProcessorDefinition $definition): GitProcessor
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        return new GitProcessor($options->enum('level', Level::class, Level::Debug));
    }
}
