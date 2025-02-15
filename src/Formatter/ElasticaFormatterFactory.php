<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Monolog\Formatter\ElasticaFormatter;
use Sirix\Monolog\FactoryInterface;

class ElasticaFormatterFactory implements FactoryInterface
{
    public function __invoke(array $options): ElasticaFormatter
    {
        $index = (string) ($options['index'] ?? null);
        $type = (string) ($options['type'] ?? null);

        return new ElasticaFormatter($index, $type);
    }
}
