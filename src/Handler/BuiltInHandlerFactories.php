<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Sirix\Monolog\Enum\HandlerType;

final class BuiltInHandlerFactories
{
    /**
     * @return array<non-empty-string, class-string<HandlerFactoryInterface>>
     */
    public static function map(): array
    {
        return [
            HandlerType::Stream->value => StreamHandlerFactory::class,
            HandlerType::RotatingFile->value => RotatingFileHandlerFactory::class,
            HandlerType::Syslog->value => SyslogHandlerFactory::class,
            HandlerType::SyslogUdp->value => SyslogUdpHandlerFactory::class,
            HandlerType::ErrorLog->value => ErrorLogHandlerFactory::class,
            HandlerType::Amqp->value => AmqpHandlerFactory::class,
            HandlerType::BrowserConsole->value => BrowserConsoleHandlerFactory::class,
            HandlerType::ChromePhp->value => ChromePHPHandlerFactory::class,
            HandlerType::CouchDb->value => CouchDBHandlerFactory::class,
            HandlerType::Cube->value => CubeHandlerFactory::class,
            HandlerType::DoctrineCouchDb->value => DoctrineCouchDBHandlerFactory::class,
            HandlerType::DynamoDb->value => DynamoDbHandlerFactory::class,
            HandlerType::Elastica->value => ElasticaHandlerFactory::class,
            HandlerType::Elasticsearch->value => ElasticsearchHandlerFactory::class,
            HandlerType::FirePhp->value => FirePHPHandlerFactory::class,
            HandlerType::FleepHook->value => FleepHookHandlerFactory::class,
            HandlerType::Flowdock->value => FlowdockHandlerFactory::class,
            HandlerType::Gelf->value => GelfHandlerFactory::class,
            HandlerType::Ifttt->value => IFTTTHandlerFactory::class,
            HandlerType::InsightOps->value => InsightOpsHandlerFactory::class,
            HandlerType::LogEntries->value => LogEntriesHandlerFactory::class,
            HandlerType::Loggly->value => LogglyHandlerFactory::class,
            HandlerType::Logmatic->value => LogmaticHandlerFactory::class,
            HandlerType::Mandrill->value => MandrillHandlerFactory::class,
            HandlerType::MongoDb->value => MongoDBHandlerFactory::class,
            HandlerType::NativeMailer->value => NativeMailerHandlerFactory::class,
            HandlerType::NewRelic->value => NewRelicHandlerFactory::class,
            HandlerType::PhpConsole->value => PHPConsoleHandlerFactory::class,
            HandlerType::Process->value => ProcessHandlerFactory::class,
            HandlerType::Pushover->value => PushoverHandlerFactory::class,
            HandlerType::Redis->value => RedisHandlerFactory::class,
            HandlerType::RedisPubSub->value => RedisPubSubHandlerFactory::class,
            HandlerType::Rollbar->value => RollbarHandlerFactory::class,
            HandlerType::SendGrid->value => SendGridHandlerFactory::class,
            HandlerType::Slack->value => SlackHandlerFactory::class,
            HandlerType::SlackWebhook->value => SlackWebhookHandlerFactory::class,
            HandlerType::Socket->value => SocketHandlerFactory::class,
            HandlerType::Sqs->value => SqsHandlerFactory::class,
            HandlerType::SymfonyMailer->value => SymfonyMailerHandlerFactory::class,
            HandlerType::TelegramBot->value => TelegramBotHandlerFactory::class,
            HandlerType::ZendMonitor->value => ZendMonitorHandlerFactory::class,
            HandlerType::Psr->value => PsrHandlerFactory::class,
            HandlerType::Test->value => TestHandlerFactory::class,
            HandlerType::Null->value => NullHandlerFactory::class,
            HandlerType::Noop->value => NoopHandlerFactory::class,
            HandlerType::Group->value => GroupHandlerFactory::class,
            HandlerType::WhatFailureGroup->value => WhatFailureGroupHandlerFactory::class,
            HandlerType::FallbackGroup->value => FallbackGroupHandlerFactory::class,
            HandlerType::Buffer->value => BufferHandlerFactory::class,
            HandlerType::Filter->value => FilterHandlerFactory::class,
            HandlerType::FingersCrossed->value => FingersCrossedHandlerFactory::class,
            HandlerType::Sampling->value => SamplingHandlerFactory::class,
            HandlerType::Deduplication->value => DeduplicationHandlerFactory::class,
            HandlerType::Overflow->value => OverflowHandlerFactory::class,
        ];
    }
}
