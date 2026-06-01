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
    public function create(ContainerInterface $container, FormatterDefinition $formatterDefinition): LogmaticFormatter
    {
        $configReader = ConfigReader::fromArray($formatterDefinition->options, self::class);
        $batchMode = $configReader->int('batch_mode', JsonFormatter::BATCH_MODE_JSON);

        if (JsonFormatter::BATCH_MODE_JSON !== $batchMode && JsonFormatter::BATCH_MODE_NEWLINES !== $batchMode) {
            throw new InvalidConfigException('Logmatic formatter option "batch_mode" must be a valid JsonFormatter batch mode.');
        }

        $logmaticFormatter = new LogmaticFormatter(
            $batchMode,
            $configReader->bool('append_newline', true),
        );

        $hostName = $configReader->optionalString('hostname');
        if (null !== $hostName && '' !== $hostName) {
            $logmaticFormatter->setHostname($hostName);
        }

        $appName = $configReader->optionalString('app_name');
        if (null !== $appName && '' !== $appName) {
            $logmaticFormatter->setAppName($appName);
        }

        return $logmaticFormatter;
    }
}
