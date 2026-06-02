<?php

declare(strict_types=1);

namespace Sirix\Monolog\Enum;

enum ConfigKey: string
{
    case Root = 'monolog';
    case LoggerServices = 'logger_services';
    case Channels = 'channels';
    case Handlers = 'handlers';
    case Formatters = 'formatters';
    case Processors = 'processors';
    case HandlerFactories = 'handler_factories';
    case FormatterFactories = 'formatter_factories';
    case ProcessorFactories = 'processor_factories';

    case Name = 'name';
    case Channel = 'channel';
    case Type = 'type';
    case Options = 'options';
    case Formatter = 'formatter';
    case Level = 'level';
    case Bubble = 'bubble';
}
