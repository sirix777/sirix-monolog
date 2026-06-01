<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Monolog\Formatter\WildfireFormatter;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\FormatterDefinition;

class WildfireFormatterFactory implements FormatterFactoryInterface
{
    public function create(ContainerInterface $container, FormatterDefinition $formatterDefinition): WildfireFormatter
    {
        $configReader = ConfigReader::fromArray($formatterDefinition->options, self::class);

        return new WildfireFormatter($configReader->optionalString('date_format'));
    }
}
