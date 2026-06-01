<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Support;

use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\ProcessorDefinition;
use Sirix\Monolog\Processor\ProcessorFactoryInterface;

final class CustomExtraProcessorFactory implements ProcessorFactoryInterface
{
    public function create(ContainerInterface $container, ProcessorDefinition $processorDefinition): ProcessorInterface
    {
        $configReader = ConfigReader::fromArray($processorDefinition->options, self::class);
        $field = $configReader->requiredNonEmptyString('field');
        $value = $configReader->required('value');

        return new class($field, $value) implements ProcessorInterface {
            public function __construct(private readonly string $field, private readonly mixed $value) {}

            public function __invoke(LogRecord $record): LogRecord
            {
                return $record->with(extra: $record->extra + [$this->field => $this->value]);
            }
        };
    }
}
