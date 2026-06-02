<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Monolog\Formatter\FluentdFormatter;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\FormatterDefinition;

class FluentdFormatterFactory implements FormatterFactoryInterface
{
    public function create(ContainerInterface $container, FormatterDefinition $formatterDefinition): FluentdFormatter
    {
        $configReader = ConfigReader::fromArray($formatterDefinition->options, self::class);

        return new FluentdFormatter($configReader->bool('level_tag', false));
    }
}
