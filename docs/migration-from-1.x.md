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

`ConfigProvider` registers `Psr\Log\LoggerInterface` as an alias to `logger.default`.

For extra logger services, register `Sirix\Monolog\Factory\LoggerFactory` and map the service id to a channel id via `logger_services`.

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
| `psrLogMessage` | `psr_log_message` |
| `memoryUsage` | `memory_usage` |
| `memoryPeak` | `memory_peak_usage` |
| `processid` | `process_id` |
| `chromePHP` | `chrome_php` |
| `mongodb` | `mongo_db` |

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

## Removed built-in handlers and processors

2.x keeps a focused built-in handler set:

- file/syslog/process/PSR handlers
- null/noop/test handlers
- wrapper handlers

Legacy integrations such as Slack, Redis, AMQP, SQS, DynamoDB, NativeMailer, MongoDB handler, and similar service-specific handlers were removed from the built-in map. If you need them, provide a custom `HandlerFactoryInterface` implementation and register it under `handler_factories`.

Git, Mercurial, and Pushover-device processors were also removed. Add them back with custom processor factories if needed.

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
