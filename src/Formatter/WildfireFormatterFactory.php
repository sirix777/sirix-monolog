<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Monolog\Formatter\WildfireFormatter;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\FormatterDefinition;

class WildfireFormatterFactory implements FormatterFactoryInterface
{
    public function create(ContainerInterface $container, FormatterDefinition $definition): WildfireFormatter
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        return new WildfireFormatter($options->optionalString('date_format'));
    }
}
