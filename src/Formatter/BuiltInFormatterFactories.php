<?php

declare(strict_types=1);

namespace Sirix\Monolog\Formatter;

use Sirix\Monolog\Enum\FormatterType;

final class BuiltInFormatterFactories
{
    /**
     * @return array<non-empty-string, class-string<FormatterFactoryInterface>>
     */
    public static function map(): array
    {
        return [
            FormatterType::Line->value => LineFormatterFactory::class,
            FormatterType::Json->value => JsonFormatterFactory::class,
            FormatterType::Html->value => HtmlFormatterFactory::class,
            FormatterType::Normalizer->value => NormalizerFormatterFactory::class,
            FormatterType::Scalar->value => ScalarFormatterFactory::class,
            FormatterType::Logstash->value => LogstashFormatterFactory::class,
            FormatterType::Wildfire->value => WildfireFormatterFactory::class,
            FormatterType::ChromePhp->value => ChromePHPFormatterFactory::class,
            FormatterType::Gelf->value => GelfMessageFormatterFactory::class,
            FormatterType::Elastica->value => ElasticaFormatterFactory::class,
            FormatterType::Elasticsearch->value => ElasticsearchFormatterFactory::class,
            FormatterType::Fluentd->value => FluentdFormatterFactory::class,
            FormatterType::GoogleCloudLogging->value => GoogleCloudLoggingFormatterFactory::class,
            FormatterType::Loggly->value => LogglyFormatterFactory::class,
            FormatterType::Flowdock->value => FlowdockFormatterFactory::class,
            FormatterType::MongoDb->value => MongoDBFormatterFactory::class,
            FormatterType::Logmatic->value => LogmaticFormatterFactory::class,
            FormatterType::Syslog->value => SyslogFormatterFactory::class,
        ];
    }
}
