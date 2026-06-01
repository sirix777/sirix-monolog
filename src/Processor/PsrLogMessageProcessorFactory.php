<?php

declare(strict_types=1);

namespace Sirix\Monolog\Processor;

use Monolog\Processor\PsrLogMessageProcessor;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\ProcessorDefinition;

class PsrLogMessageProcessorFactory implements ProcessorFactoryInterface
{
    public function create(ContainerInterface $container, ProcessorDefinition $definition): PsrLogMessageProcessor
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        return new PsrLogMessageProcessor(
            $options->optionalString('date_format'),
            $options->bool('remove_used_context_fields', false),
        );
    }
}
