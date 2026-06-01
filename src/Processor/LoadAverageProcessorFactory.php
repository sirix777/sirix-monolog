<?php

declare(strict_types=1);

namespace Sirix\Monolog\Processor;

use Monolog\Processor\LoadAverageProcessor;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\ProcessorDefinition;
use Sirix\Monolog\Exception\InvalidConfigException;

use function in_array;

class LoadAverageProcessorFactory implements ProcessorFactoryInterface
{
    public function create(ContainerInterface $container, ProcessorDefinition $processorDefinition): LoadAverageProcessor
    {
        $configReader = ConfigReader::fromArray($processorDefinition->options, self::class);
        $avgSystemLoad = $configReader->int('avg_system_load', LoadAverageProcessor::LOAD_1_MINUTE);

        if (! in_array($avgSystemLoad, [
            LoadAverageProcessor::LOAD_1_MINUTE,
            LoadAverageProcessor::LOAD_5_MINUTE,
            LoadAverageProcessor::LOAD_15_MINUTE,
        ], true)) {
            throw new InvalidConfigException('Load average processor option "avg_system_load" must be one of 0, 1, or 2.');
        }

        return new LoadAverageProcessor($avgSystemLoad);
    }
}
