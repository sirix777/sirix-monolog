<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Monolog\Formatter\LineFormatter;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\FormatterDefinition;

class LineFormatterFactory implements FormatterFactoryInterface
{
    public function create(ContainerInterface $container, FormatterDefinition $definition): LineFormatter
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        return new LineFormatter(
            $options->optionalString('format'),
            $options->optionalString('date_format'),
            $options->bool('allow_inline_line_breaks', false),
            $options->bool('ignore_empty_context_and_extra', false),
            $options->bool('include_stacktraces', false),
        );
    }
}
