<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Monolog\Formatter\MongoDBFormatter;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\FormatterDefinition;

class MongoDBFormatterFactory implements FormatterFactoryInterface
{
    public function create(ContainerInterface $container, FormatterDefinition $formatterDefinition): MongoDBFormatter
    {
        $configReader = ConfigReader::fromArray($formatterDefinition->options, self::class);

        return new MongoDBFormatter(
            $configReader->int('max_nesting_level', 3),
            $configReader->bool('exception_trace_as_string', true),
        );
    }
}
