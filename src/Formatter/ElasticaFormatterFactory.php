<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Monolog\Formatter\ElasticaFormatter;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\FormatterDefinition;

class ElasticaFormatterFactory implements FormatterFactoryInterface
{
    public function create(ContainerInterface $container, FormatterDefinition $formatterDefinition): ElasticaFormatter
    {
        $configReader = ConfigReader::fromArray($formatterDefinition->options, self::class);

        return new ElasticaFormatter(
            $configReader->requiredNonEmptyString('index'),
            $configReader->optionalString('document_type'),
        );
    }
}
