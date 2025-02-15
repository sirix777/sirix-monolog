<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Sirix\Monolog\MapperInterface;

class FormatterMapper implements MapperInterface
{
    public function map(string $type): ?string
    {
        return match ($type) {
            'line' => LineFormatterFactory::class,
            'html' => HtmlFormatterFactory::class,
            'normalizer' => NormalizerFormatterFactory::class,
            'scalar' => ScalarFormatterFactory::class,
            'json' => JsonFormatterFactory::class,
            'wildfire' => WildfireFormatterFactory::class,
            'chromePHP' => ChromePHPFormatterFactory::class,
            'gelf' => GelfMessageFormatterFactory::class,
            'logstash' => LogstashFormatterFactory::class,
            'elastica' => ElasticaFormatterFactory::class,
            'loggly' => LogglyFormatterFactory::class,
            'flowdock' => FlowdockFormatterFactory::class,
            'mongodb' => MongoDBFormatterFactory::class,
            'logmatic' => LogmaticFormatterFactory::class,
            default => null,
        };
    }
}
