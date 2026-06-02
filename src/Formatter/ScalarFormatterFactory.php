<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Monolog\Formatter\ScalarFormatter;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\FormatterDefinition;

class ScalarFormatterFactory implements FormatterFactoryInterface
{
    public function create(ContainerInterface $container, FormatterDefinition $formatterDefinition): ScalarFormatter
    {
        $configReader = ConfigReader::fromArray($formatterDefinition->options, self::class);

        return new ScalarFormatter($configReader->optionalString('date_format'));
    }
}
