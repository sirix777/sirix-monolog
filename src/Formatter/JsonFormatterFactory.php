<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Monolog\Formatter\JsonFormatter;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\FormatterDefinition;
use Sirix\Monolog\Exception\InvalidConfigException;

class JsonFormatterFactory implements FormatterFactoryInterface
{
    public function create(ContainerInterface $container, FormatterDefinition $definition): JsonFormatter
    {
        $options = ConfigReader::fromArray($definition->options, self::class);
        $batchMode = $options->int('batch_mode', JsonFormatter::BATCH_MODE_JSON);

        if (JsonFormatter::BATCH_MODE_JSON !== $batchMode && JsonFormatter::BATCH_MODE_NEWLINES !== $batchMode) {
            throw new InvalidConfigException('Json formatter option "batch_mode" must be a valid JsonFormatter batch mode.');
        }

        return new JsonFormatter(
            $batchMode,
            $options->bool('append_newline', true),
            $options->bool('ignore_empty_context_and_extra', false),
            $options->bool('include_stacktraces', false),
        );
    }
}
