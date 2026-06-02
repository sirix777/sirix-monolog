<?php

declare(strict_types=1);

namespace Sirix\Monolog\Processor;

use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\ProcessorDefinition;
use Sirix\Monolog\Exception\InvalidConfigException;

class UidProcessorFactory implements ProcessorFactoryInterface
{
    public function create(ContainerInterface $container, ProcessorDefinition $processorDefinition): UidProcessor
    {
        $configReader = ConfigReader::fromArray($processorDefinition->options, self::class);
        $length = $configReader->int('length', 7);

        if ($length < 1 || $length > 32) {
            throw new InvalidConfigException('Uid processor option "length" must be between 1 and 32.');
        }

        return new UidProcessor($length);
    }
}
