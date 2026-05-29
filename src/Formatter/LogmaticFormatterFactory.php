<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Monolog\Formatter\JsonFormatter;
use Monolog\Formatter\LogmaticFormatter;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\FormatterDefinition;
use Sirix\Monolog\Exception\InvalidConfigException;

class LogmaticFormatterFactory implements FormatterFactoryInterface
{
    public function create(ContainerInterface $container, FormatterDefinition $definition): LogmaticFormatter
    {
        $options = ConfigReader::fromArray($definition->options, self::class);
        $batchMode = $options->int('batch_mode', JsonFormatter::BATCH_MODE_JSON);

        if (JsonFormatter::BATCH_MODE_JSON !== $batchMode && JsonFormatter::BATCH_MODE_NEWLINES !== $batchMode) {
            throw new InvalidConfigException('Logmatic formatter option "batch_mode" must be a valid JsonFormatter batch mode.');
        }

        $formatter = new LogmaticFormatter(
            $batchMode,
            $options->bool('append_newline', true),
        );

        $hostName = $options->optionalString('hostname');
        if (null !== $hostName && '' !== $hostName) {
            $formatter->setHostname($hostName);
        }

        $appName = $options->optionalString('app_name');
        if (null !== $appName && '' !== $appName) {
            $formatter->setAppName($appName);
        }

        return $formatter;
    }
}
