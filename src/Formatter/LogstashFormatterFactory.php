<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Monolog\Formatter\LogstashFormatter;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\FormatterDefinition;

class LogstashFormatterFactory implements FormatterFactoryInterface
{
    public function create(ContainerInterface $container, FormatterDefinition $formatterDefinition): LogstashFormatter
    {
        $configReader = ConfigReader::fromArray($formatterDefinition->options, self::class);

        return new LogstashFormatter(
            $configReader->string('application_name', ''),
            $configReader->optionalString('system_name'),
            $configReader->string('extra_key', 'extra'),
            $configReader->string('context_key', 'context'),
        );
    }
}
