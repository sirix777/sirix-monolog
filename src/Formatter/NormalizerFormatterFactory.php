<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Monolog\Formatter\NormalizerFormatter;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\FormatterDefinition;

class NormalizerFormatterFactory implements FormatterFactoryInterface
{
    public function create(ContainerInterface $container, FormatterDefinition $formatterDefinition): NormalizerFormatter
    {
        $configReader = ConfigReader::fromArray($formatterDefinition->options, self::class);

        return new NormalizerFormatter($configReader->optionalString('date_format'));
    }
}
