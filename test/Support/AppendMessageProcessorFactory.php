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
    public function create(ContainerInterface $container, ProcessorDefinition $definition): ProcessorInterface
    {
        $options = ConfigReader::fromArray($definition->options, self::class);
        $suffix = $options->requiredNonEmptyString('suffix');

        return new class($suffix) implements ProcessorInterface {
            public function __construct(private readonly string $suffix) {}

            public function __invoke(LogRecord $record): LogRecord
            {
                return $record->with(message: $record->message . $this->suffix);
            }
        };
    }
}
