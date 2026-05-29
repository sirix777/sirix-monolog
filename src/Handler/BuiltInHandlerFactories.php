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
            HandlerType::ErrorLog->value => ErrorLogHandlerFactory::class,
            HandlerType::Process->value => ProcessHandlerFactory::class,
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
