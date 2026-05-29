<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Monolog\Formatter\LogstashFormatter;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\FormatterDefinition;

class LogstashFormatterFactory implements FormatterFactoryInterface
{
    public function create(ContainerInterface $container, FormatterDefinition $definition): LogstashFormatter
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        return new LogstashFormatter(
            $options->string('application_name', ''),
            $options->optionalString('system_name'),
            $options->string('extra_key', 'extra'),
            $options->string('context_key', 'context'),
        );
    }
}
