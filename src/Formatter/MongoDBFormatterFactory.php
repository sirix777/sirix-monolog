<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Monolog\Formatter\MongoDBFormatter;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\FormatterDefinition;

class MongoDBFormatterFactory implements FormatterFactoryInterface
{
    public function create(ContainerInterface $container, FormatterDefinition $definition): MongoDBFormatter
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        return new MongoDBFormatter(
            $options->int('max_nesting_level', 3),
            $options->bool('exception_trace_as_string', true),
        );
    }
}
