<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Monolog\Formatter\FlowdockFormatter;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\FormatterDefinition;

class FlowdockFormatterFactory implements FormatterFactoryInterface
{
    public function create(ContainerInterface $container, FormatterDefinition $definition): FlowdockFormatter
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        return new FlowdockFormatter(
            $options->requiredNonEmptyString('source'),
            $options->requiredNonEmptyString('source_email'),
        );
    }
}
