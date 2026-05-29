<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Monolog\Formatter\HtmlFormatter;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\FormatterDefinition;

class HtmlFormatterFactory implements FormatterFactoryInterface
{
    public function create(ContainerInterface $container, FormatterDefinition $definition): HtmlFormatter
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        return new HtmlFormatter($options->optionalString('date_format'));
    }
}
