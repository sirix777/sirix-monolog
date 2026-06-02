<?php

declare(strict_types=1);

namespace Sirix\Monolog\Enum;

enum HandlerType: string
{
    case Stream = 'stream';
    case RotatingFile = 'rotating_file';
    case Syslog = 'syslog';
    case SyslogUdp = 'syslog_udp';
    case ErrorLog = 'error_log';
    case Amqp = 'amqp';
    case BrowserConsole = 'browser_console';
    case ChromePhp = 'chrome_php';
    case CouchDb = 'couch_db';
    case Cube = 'cube';
    case DoctrineCouchDb = 'doctrine_couch_db';
    case DynamoDb = 'dynamo_db';
    case Elastica = 'elastica';
    case Elasticsearch = 'elasticsearch';
    case FirePhp = 'fire_php';
    case FleepHook = 'fleep_hook';
    case Flowdock = 'flowdock';
    case Gelf = 'gelf';
    case Ifttt = 'ifttt';
    case InsightOps = 'insight_ops';
    case LogEntries = 'log_entries';
    case Loggly = 'loggly';
    case Logmatic = 'logmatic';
    case Mandrill = 'mandrill';
    case MongoDb = 'mongo_db';
    case NativeMailer = 'native_mailer';
    case NewRelic = 'new_relic';
    case PhpConsole = 'php_console';
    case Process = 'process';
    case Pushover = 'pushover';
    case Redis = 'redis';
    case RedisPubSub = 'redis_pub_sub';
    case Rollbar = 'rollbar';
    case SendGrid = 'send_grid';
    case Slack = 'slack';
    case SlackWebhook = 'slack_webhook';
    case Socket = 'socket';
    case Sqs = 'sqs';
    case SymfonyMailer = 'symfony_mailer';
    case TelegramBot = 'telegram_bot';
    case ZendMonitor = 'zend_monitor';
    case Psr = 'psr';
    case Test = 'test';
    case Null = 'null';
    case Noop = 'noop';
    case Group = 'group';
    case WhatFailureGroup = 'what_failure_group';
    case FallbackGroup = 'fallback_group';
    case Buffer = 'buffer';
    case Filter = 'filter';
    case FingersCrossed = 'fingers_crossed';
    case Sampling = 'sampling';
    case Deduplication = 'deduplication';
    case Overflow = 'overflow';
}
