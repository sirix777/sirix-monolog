<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Sirix\Monolog\MapperInterface;

use function strtolower;

class HandlerMapper implements MapperInterface
{
    public function map(string $type): ?string
    {
        $type = strtolower($type);

        return match ($type) {
            'stream' => StreamHandlerFactory::class,
            'rotating' => RotatingFileHandlerFactory::class,
            'syslog' => SyslogHandlerFactory::class,
            'errorlog' => ErrorLogHandlerFactory::class,
            'nativemailer' => NativeMailerHandlerFactory::class,
            'pushover' => PushoverHandlerFactory::class,
            'flowdock' => FlowdockHandlerFactory::class,
            'slackwebhook' => SlackWebhookHandlerFactory::class,
            'slack' => SlackHandlerFactory::class,
            'mandrill' => MandrillHandlerFactory::class,
            'fleephook' => FleepHookHandlerFactory::class,
            'ifttt' => IFTTTHandlerFactory::class,
            'socket' => SocketHandlerFactory::class,
            'amqp' => AmqpHandlerFactory::class,
            'gelf' => GelfHandlerFactory::class,
            'zend' => ZendMonitorHandlerFactory::class,
            'newrelic' => NewRelicHandlerFactory::class,
            'loggly' => LogglyHandlerFactory::class,
            'syslogudp' => SyslogUdpHandlerFactory::class,
            'logentries' => LogEntriesHandlerFactory::class,
            'firephp' => FirePHPHandlerFactory::class,
            'chromephp' => ChromePHPHandlerFactory::class,
            'browserconsole' => BrowserConsoleHandlerFactory::class,
            'redis' => RedisHandlerFactory::class,
            'mongo' => MongoDBHandlerFactory::class,
            'couchdb' => CouchDBHandlerFactory::class,
            'doctrinecouchdb' => DoctrineCouchDBHandlerFactory::class,
            'elastica' => ElasticaHandlerFactory::class,
            'dynamodb' => DynamoDbHandlerFactory::class,
            'fingerscrossed' => FingersCrossedHandlerFactory::class,
            'deduplication' => DeduplicationHandlerFactory::class,
            'whatfailuregrouphandler' => WhatFailureGroupHandlerFactory::class,
            'buffer' => BufferHandlerFactory::class,
            'group' => GroupHandlerFactory::class,
            'filter' => FilterHandlerFactory::class,
            'sampling' => SamplingHandlerFactory::class,
            'null' => NullHandlerFactory::class,
            'psr' => PsrHandlerFactory::class,
            'process' => ProcessHandlerFactory::class,
            'sendgrid' => SendGridHandlerFactory::class,
            'telegrambot' => TelegramBotHandlerFactory::class,
            'insightops' => InsightOpsHandlerFactory::class,
            'logmatic' => LogmaticHandlerFactory::class,
            'sqs' => SqsHandlerFactory::class,
            'fallbackgroup' => FallbackGroupHandlerFactory::class,
            'noop' => NoopHandlerFactory::class,
            'overflow' => OverflowHandlerFactory::class,
            'test' => TestHandlerFactory::class,
            default => null,
        };
    }
}
