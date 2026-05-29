<?php

declare(strict_types=1);

namespace Sirix\Monolog\Enum;

enum HandlerType: string
{
    case Stream = 'stream';
    case RotatingFile = 'rotating_file';
    case Syslog = 'syslog';
    case ErrorLog = 'error_log';
    case Process = 'process';
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
