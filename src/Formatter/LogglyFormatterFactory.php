<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Monolog\Formatter\LogglyFormatter;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\FormatterDefinition;
use Sirix\Monolog\Exception\InvalidConfigException;

class LogglyFormatterFactory implements FormatterFactoryInterface
{
    public function create(ContainerInterface $container, FormatterDefinition $definition): LogglyFormatter
    {
        $options = ConfigReader::fromArray($definition->options, self::class);
        $batchMode = $options->int('batch_mode', LogglyFormatter::BATCH_MODE_NEWLINES);

        if (LogglyFormatter::BATCH_MODE_JSON !== $batchMode && LogglyFormatter::BATCH_MODE_NEWLINES !== $batchMode) {
            throw new InvalidConfigException('Loggly formatter option "batch_mode" must be a valid JsonFormatter batch mode.');
        }

        return new LogglyFormatter(
            $batchMode,
            $options->bool('append_newline', false),
        );
    }
}
