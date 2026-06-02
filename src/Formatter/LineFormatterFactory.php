<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Monolog\Formatter\LineFormatter;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\FormatterDefinition;

class LineFormatterFactory implements FormatterFactoryInterface
{
    public function create(ContainerInterface $container, FormatterDefinition $formatterDefinition): LineFormatter
    {
        $configReader = ConfigReader::fromArray($formatterDefinition->options, self::class);

        return new LineFormatter(
            $configReader->optionalString('format'),
            $configReader->optionalString('date_format'),
            $configReader->bool('allow_inline_line_breaks', false),
            $configReader->bool('ignore_empty_context_and_extra', false),
            $configReader->bool('include_stacktraces', false),
        );
    }
}
