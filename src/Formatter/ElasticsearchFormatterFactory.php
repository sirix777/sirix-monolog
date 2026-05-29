<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Monolog\Formatter\ElasticsearchFormatter;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\FormatterDefinition;

class ElasticsearchFormatterFactory implements FormatterFactoryInterface
{
    public function create(ContainerInterface $container, FormatterDefinition $definition): ElasticsearchFormatter
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        return new ElasticsearchFormatter(
            $options->requiredNonEmptyString('index'),
            $options->requiredNonEmptyString('document_type'),
        );
    }
}
