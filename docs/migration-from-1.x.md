# Migration from 1.x

Version 2.x is a breaking release. It removes the legacy generic PSR-11 factory layer and keeps a smaller Mezzio-first architecture.

## Removed APIs

The following APIs were removed:

- `Sirix\Monolog\Module`
- `Sirix\Monolog\MonologFactory`
- `Sirix\Monolog\ChannelChanger`
- `Sirix\Monolog\ChannelChangerFactory`
- legacy service managers under `Sirix\Monolog\Service\*`
- legacy config wrappers such as `MainConfig`, `HandlerConfig`, `FormatterConfig`, `ProcessorConfig`, `ChannelConfig`
- legacy mapper classes
- `FactoryInterface`
- `ContainerAwareInterface`
- `ContainerTrait`
- `ServiceTrait`
- factory `__invoke(array $options)` methods

Use `Sirix\Monolog\ConfigProvider` and the strict factory interfaces instead.

## Configuration changes

### Service registration

Before:

```php
'logger' => Sirix\Monolog\MonologFactory::class,
```

After:

```php
Sirix\Monolog\ConfigProvider::class,
```

`ConfigProvider` registers `logger`, `Monolog\Logger`, and `Psr\Log\LoggerInterface` for the default logger service.

For extra logger services, register `Sirix\Monolog\Factory\LoggerFactory` and map the service id to a channel id via `logger_services`. Prefer service ids without dots, such as `logger_audit`, because some containers treat dots as path separators.

### Type names

Use enum values or the new `snake_case` type strings.

Examples:

| 1.x style | 2.x style |
| --- | --- |
| `rotating` | `rotating_file` |
| `errorlog` | `error_log` |
| `whatFailureGroup` | `what_failure_group` |
| `fallbackgroup` | `fallback_group` |
| `fingersCrossed` | `fingers_crossed` |
| `telegrambot` | `telegram_bot` |
| `syslogudp` | `syslog_udp` |
| `nativeMailer` | `native_mailer` |
| `slackwebhook` | `slack_webhook` |
| `sendgrid` | `send_grid` |
| `newrelic` | `new_relic` |
| `redisPubSub` | `redis_pub_sub` |
| `psrLogMessage` | `psr_log_message` |
| `memoryUsage` | `memory_usage` |
| `memoryPeak` | `memory_peak_usage` |
| `processid` | `process_id` |
| `loadAverage` | `load_average` |
| `chromePHP` | `chrome_php` |
| `mongodb` | `mongo_db` |
| `googleCloudLogging` | `google_cloud_logging` |

Prefer enum cases such as `HandlerType::Stream`, `FormatterType::Line`, and `ProcessorType::PsrLogMessage`.

### Option names

Built-in factory options now use `snake_case`.

Examples:

| 1.x option | 2.x option |
| --- | --- |
| `dateFormat` | `date_format` |
| `allowInlineLineBreaks` | `allow_inline_line_breaks` |
| `ignoreEmptyContextAndExtra` | `ignore_empty_context_and_extra` |
| `batchMode` | `batch_mode` |
| `appendNewline` | `append_newline` |
| `filePermission` | `file_permission` |
| `useLocking` | `use_locking` |
| `maxFiles` | `max_files` |
| `messageType` | `message_type` |
| `expandNewlines` | `expand_newlines` |
| `bufferLimit` | `buffer_limit` |
| `flushOnOverflow` | `flush_on_overflow` |
| `activationStrategy` | `activation_strategy` |
| `stopBuffering` | `stop_buffering` |
| `passthruLevel` | `passthru_level` |
| `deduplicationStore` | `deduplication_store` |
| `deduplicationLevel` | `deduplication_level` |
| `thresholdMap` | `threshold_map` |
| `useDefaultRules` | `use_default_rules` |
| `objectViewMode` | `object_view_mode` |
| `lengthLimit` | `length_limit` |
| `maxDepth` | `max_depth` |

## Built-in handlers and processors

2.x supports the concrete handlers, formatters, and processors shipped with Monolog 3 using `snake_case` type names.

Service-specific handlers such as Slack, Redis, AMQP, SQS, DynamoDB, MongoDB, and similar integrations may require optional extensions, SDKs, or container services; see `docs/handlers.md` for required options.

The Git and Mercurial processors are available as `git` and `mercurial`. The legacy Pushover-device processor is not part of Monolog 3's default processor set and remains unsupported.

## Custom factories

Before, factories implemented `FactoryInterface` and were called through `__invoke(array $options)`.

In 2.x, implement one of the strict interfaces:

```php
<?php

use Monolog\Handler\HandlerInterface;
use Psr\Container\ContainerInterface;
use Sirix\Monolog\Config\HandlerDefinition;
use Sirix\Monolog\Handler\HandlerFactoryInterface;

final class AuditHandlerFactory implements HandlerFactoryInterface
{
    public function create(ContainerInterface $container, HandlerDefinition $definition): HandlerInterface
    {
        // Build and return a Monolog handler.
    }
}
```

Then register it:

```php
<?php

use App\Logging\AuditHandlerFactory;
use Sirix\Monolog\Enum\ConfigKey;

return [
    ConfigKey::Root->value => [
        ConfigKey::HandlerFactories->value => [
            'audit' => AuditHandlerFactory::class,
        ],
    ],
];
```
