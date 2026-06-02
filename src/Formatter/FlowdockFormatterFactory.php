<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Monolog\Formatter\FlowdockFormatter;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\FormatterDefinition;

class FlowdockFormatterFactory implements FormatterFactoryInterface
{
    public function create(ContainerInterface $container, FormatterDefinition $formatterDefinition): FlowdockFormatter
    {
        $configReader = ConfigReader::fromArray($formatterDefinition->options, self::class);

        return new FlowdockFormatter(
            $configReader->requiredNonEmptyString('source'),
            $configReader->requiredNonEmptyString('source_email'),
        );
    }
}
