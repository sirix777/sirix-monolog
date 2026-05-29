<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Monolog\Formatter\FluentdFormatter;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\FormatterDefinition;

class FluentdFormatterFactory implements FormatterFactoryInterface
{
    public function create(ContainerInterface $container, FormatterDefinition $definition): FluentdFormatter
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        return new FluentdFormatter($options->bool('level_tag', false));
    }
}
