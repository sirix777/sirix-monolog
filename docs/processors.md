# Processors

Built-in processors are configured under `monolog.processors`. Processor option names use `snake_case`.

Processors run for every record that reaches the logger or handler they are attached to, unless the processor itself filters by level. Keep expensive processors off hot `debug`/`info` paths in production.

## `psr_log_message`

Creates `Monolog\Processor\PsrLogMessageProcessor`.

Options:

- `date_format` optional
- `remove_used_context_fields` default: `false`

## `closure_context`

Creates `Monolog\Processor\ClosureContextProcessor`.

No options.

## `git`

Creates `Monolog\Processor\GitProcessor`.

Options:

- `level` default: `Level::Debug`

The Git processor shells out to `git` on the first matching record and caches the result afterwards. Prefer enabling it only when repository metadata is useful in runtime logs.

## `introspection`

Creates `Monolog\Processor\IntrospectionProcessor`.

Options:

- `level` default: `Level::Debug`
- `skip_classes_partials` default: `[]`
- `skip_stack_frames_count` default: `0`

This processor uses `debug_backtrace()` for matching records. In production, prefer a higher threshold such as `Level::Error` or attach it only to handlers that process exceptional records.

## `load_average`

Creates `Monolog\Processor\LoadAverageProcessor`.

Options:

- `avg_system_load` default: `LoadAverageProcessor::LOAD_1_MINUTE`

`avg_system_load` must be one of:

- `LoadAverageProcessor::LOAD_1_MINUTE`
- `LoadAverageProcessor::LOAD_5_MINUTE`
- `LoadAverageProcessor::LOAD_15_MINUTE`

## `mercurial`

Creates `Monolog\Processor\MercurialProcessor`.

Options:

- `level` default: `Level::Debug`

The Mercurial processor shells out to `hg` on the first matching record and caches the result afterwards. Prefer enabling it only when repository metadata is useful in runtime logs.

## `web`

Creates `Monolog\Processor\WebProcessor`.

Options:

- `server_data` optional: array, `ArrayAccess`, service id, or `null`
- `extra_fields` optional: array or `null`

## `memory_usage`

Creates `Monolog\Processor\MemoryUsageProcessor`.

Options:

- `use_formatting` default: `true`
- `real_usage` default: `true`

## `memory_peak_usage`

Creates `Monolog\Processor\MemoryPeakUsageProcessor`.

Options:

- `use_formatting` default: `true`
- `real_usage` default: `true`

## `process_id`

Creates `Monolog\Processor\ProcessIdProcessor`.

No options.

## `uid`

Creates `Monolog\Processor\UidProcessor`.

Options:

- `length` default: `7`

## `hostname`

Creates `Monolog\Processor\HostnameProcessor`.

No options.

## `tags`

Creates `Monolog\Processor\TagProcessor`.

Options:

- `tags` default: `[]`

## Custom processors

Register custom processor types under `monolog.processor_factories`. The factory class must implement `Sirix\Monolog\Processor\ProcessorFactoryInterface`.

```php
<?php

namespace App\Logging;

use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;

final class TenantProcessor implements ProcessorInterface
{
    public function __construct(private TenantContext $tenantContext) {}

    public function __invoke(LogRecord $record): LogRecord
    {
        return $record->with(extra: $record->extra + [
            'tenant_id' => $this->tenantContext->id(),
        ]);
    }
}
```

```php
<?php

namespace App\Logging;

use Monolog\Processor\ProcessorInterface;
use Psr\Container\ContainerInterface;
use Sirix\Monolog\Config\ProcessorDefinition;
use Sirix\Monolog\Processor\ProcessorFactoryInterface;

final class TenantProcessorFactory implements ProcessorFactoryInterface
{
    public function create(ContainerInterface $container, ProcessorDefinition $definition): ProcessorInterface
    {
        return new TenantProcessor(
            $container->get(TenantContext::class),
        );
    }
}
```

```php
<?php

use App\Logging\TenantProcessorFactory;
use Sirix\Monolog\Enum\ConfigKey;
use Sirix\Monolog\Enum\HandlerType;

return [
    ConfigKey::Root->value => [
        ConfigKey::ProcessorFactories->value => [
            'tenant' => TenantProcessorFactory::class,
        ],
        ConfigKey::Processors->value => [
            'tenant' => [
                ConfigKey::Type->value => 'tenant',
            ],
        ],
        ConfigKey::Channels->value => [
            'default' => [
                ConfigKey::Handlers->value => ['stream'],
                ConfigKey::Processors->value => ['tenant'],
            ],
        ],
        ConfigKey::Handlers->value => [
            'stream' => [
                ConfigKey::Type->value => HandlerType::Stream,
                ConfigKey::Options->value => [
                    'stream' => 'php://stderr',
                ],
            ],
        ],
    ],
];
```

The factory receives the container and the `ProcessorDefinition`, including the configured processor `options`.

## `redactor`

Creates `Sirix\Redaction\Bridge\Monolog\RedactorProcessor`.

If the container has a `Sirix\Redaction\RedactorInterface` service, that service is used. Otherwise a `Sirix\Redaction\Redactor` is created from options.

Options when creating a redactor from config:

- `rules` default: `[]`
- `use_default_rules` default: `true`
- `replacement` optional
- `template` optional
- `length_limit` optional int or null
- `object_view_mode` optional `Sirix\Redaction\Enum\ObjectViewModeEnum`
- `max_depth` optional int or null
- `max_items_per_container` optional int or null
- `max_total_nodes` optional int or null
- `on_limit_exceeded_callback` optional callable or null
- `overflow_placeholder` optional

Example:

```php
<?php

use Sirix\Monolog\Enum\ConfigKey;
use Sirix\Monolog\Enum\ProcessorType;
use Sirix\Redaction\Rule\FullMaskRule;

return [
    ConfigKey::Root->value => [
        ConfigKey::Processors->value => [
            'redactor' => [
                ConfigKey::Type->value => ProcessorType::Redactor,
                ConfigKey::Options->value => [
                    'use_default_rules' => false,
                    'rules' => [
                        'password' => new FullMaskRule(),
                    ],
                ],
            ],
        ],
    ],
];
```
