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
    public function create(ContainerInterface $container, FormatterDefinition $formatterDefinition): JsonFormatter
    {
        $configReader = ConfigReader::fromArray($formatterDefinition->options, self::class);
        $batchMode = $configReader->int('batch_mode', JsonFormatter::BATCH_MODE_JSON);

        if (JsonFormatter::BATCH_MODE_JSON !== $batchMode && JsonFormatter::BATCH_MODE_NEWLINES !== $batchMode) {
            throw new InvalidConfigException('Json formatter option "batch_mode" must be a valid JsonFormatter batch mode.');
        }

        return new JsonFormatter(
            $batchMode,
            $configReader->bool('append_newline', true),
            $configReader->bool('ignore_empty_context_and_extra', false),
            $configReader->bool('include_stacktraces', false),
        );
    }
}
