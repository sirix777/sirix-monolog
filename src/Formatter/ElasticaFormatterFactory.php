<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Monolog\Formatter\ElasticaFormatter;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\FormatterDefinition;

class ElasticaFormatterFactory implements FormatterFactoryInterface
{
    public function create(ContainerInterface $container, FormatterDefinition $definition): ElasticaFormatter
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        return new ElasticaFormatter(
            $options->requiredNonEmptyString('index'),
            $options->optionalString('document_type'),
        );
    }
}
