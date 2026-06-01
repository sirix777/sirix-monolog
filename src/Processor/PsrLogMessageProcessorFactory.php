<?php

declare(strict_types=1);

namespace Sirix\Monolog\Processor;

use Monolog\Processor\PsrLogMessageProcessor;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\ProcessorDefinition;

class PsrLogMessageProcessorFactory implements ProcessorFactoryInterface
{
    public function create(ContainerInterface $container, ProcessorDefinition $processorDefinition): PsrLogMessageProcessor
    {
        $configReader = ConfigReader::fromArray($processorDefinition->options, self::class);

        return new PsrLogMessageProcessor(
            $configReader->optionalString('date_format'),
            $configReader->bool('remove_used_context_fields', false),
        );
    }
}
