<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Monolog\Formatter\ScalarFormatter;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\FormatterDefinition;

class ScalarFormatterFactory implements FormatterFactoryInterface
{
    public function create(ContainerInterface $container, FormatterDefinition $definition): ScalarFormatter
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        return new ScalarFormatter($options->optionalString('date_format'));
    }
}
