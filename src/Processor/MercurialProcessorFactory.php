<?php

declare(strict_types=1);

namespace Sirix\Monolog\Processor;

use Monolog\Level;
use Monolog\Processor\MercurialProcessor;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\ProcessorDefinition;

class MercurialProcessorFactory implements ProcessorFactoryInterface
{
    public function create(ContainerInterface $container, ProcessorDefinition $processorDefinition): MercurialProcessor
    {
        $configReader = ConfigReader::fromArray($processorDefinition->options, self::class);

        return new MercurialProcessor($configReader->enum('level', Level::class, Level::Debug));
    }
}
