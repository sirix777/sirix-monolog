<?php

declare(strict_types=1);

namespace Sirix\Monolog\Enum;

enum FormatterType: string
{
    case Line = 'line';
    case Json = 'json';
    case Html = 'html';
    case Normalizer = 'normalizer';
    case Scalar = 'scalar';
    case Logstash = 'logstash';
    case Wildfire = 'wildfire';
    case ChromePhp = 'chrome_php';
    case Gelf = 'gelf';
    case Elastica = 'elastica';
    case Elasticsearch = 'elasticsearch';
    case Fluentd = 'fluentd';
    case GoogleCloudLogging = 'google_cloud_logging';
    case Loggly = 'loggly';
    case Flowdock = 'flowdock';
    case MongoDb = 'mongo_db';
    case Logmatic = 'logmatic';
    case Syslog = 'syslog';
}
