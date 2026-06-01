<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Monolog\Formatter\SyslogFormatter;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\FormatterDefinition;

class SyslogFormatterFactory implements FormatterFactoryInterface
{
    public function create(ContainerInterface $container, FormatterDefinition $formatterDefinition): SyslogFormatter
    {
        $configReader = ConfigReader::fromArray($formatterDefinition->options, self::class);

        return new SyslogFormatter($configReader->string('application_name', '-'));
    }
}
