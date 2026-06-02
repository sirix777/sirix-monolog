<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Support;

use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\ProcessorDefinition;
use Sirix\Monolog\Processor\ProcessorFactoryInterface;

final class AppendMessageProcessorFactory implements ProcessorFactoryInterface
{
    public function create(ContainerInterface $container, ProcessorDefinition $processorDefinition): ProcessorInterface
    {
        $configReader = ConfigReader::fromArray($processorDefinition->options, self::class);
        $suffix = $configReader->requiredNonEmptyString('suffix');

        return new class($suffix) implements ProcessorInterface {
            public function __construct(private readonly string $suffix) {}

            public function __invoke(LogRecord $record): LogRecord
            {
                return $record->with(message: $record->message . $this->suffix);
            }
        };
    }
}
