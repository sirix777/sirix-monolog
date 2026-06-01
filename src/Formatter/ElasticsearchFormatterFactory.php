<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Monolog\Formatter\ElasticsearchFormatter;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\FormatterDefinition;

class ElasticsearchFormatterFactory implements FormatterFactoryInterface
{
    public function create(ContainerInterface $container, FormatterDefinition $formatterDefinition): ElasticsearchFormatter
    {
        $configReader = ConfigReader::fromArray($formatterDefinition->options, self::class);

        return new ElasticsearchFormatter(
            $configReader->requiredNonEmptyString('index'),
            $configReader->requiredNonEmptyString('document_type'),
        );
    }
}
