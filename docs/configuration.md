# Configuration

All package configuration lives under the `monolog` root key. The recommended way to avoid typos is to use `Sirix\Monolog\Enum\ConfigKey` constants.

```php
<?php

use Sirix\Monolog\Enum\ConfigKey;

return [
    ConfigKey::Root->value => [
        ConfigKey::LoggerServices->value => [],
        ConfigKey::Channels->value => [],
        ConfigKey::Handlers->value => [],
        ConfigKey::Formatters->value => [],
        ConfigKey::Processors->value => [],
        ConfigKey::HandlerFactories->value => [],
        ConfigKey::FormatterFactories->value => [],
        ConfigKey::ProcessorFactories->value => [],
    ],
];
```

## Defaults

If no configuration is provided, the package creates a safe default logger:

- service `logger` → channel `default`
- service `Monolog\Logger` → channel `default`
- service `Psr\Log\LoggerInterface` → channel `default`
- channel `default` uses name `app`
- channel `default` uses a `noop` handler

This means an unconfigured logger is safe to request and will discard records.

## Logger services

`logger_services` maps container service ids to channel ids.

```php
<?php

use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Sirix\Monolog\Enum\ConfigKey;

return [
    ConfigKey::Root->value => [
        ConfigKey::LoggerServices->value => [
            'logger' => 'default',
            'logger_audit' => 'audit',
            Logger::class => 'default',
            LoggerInterface::class => 'default',
        ],
    ],
];
```

`ConfigProvider` registers `logger`, `Monolog\Logger`, and `Psr\Log\LoggerInterface`. If you add custom logger service ids, register them with `Sirix\Monolog\Factory\LoggerFactory` in your container.

Prefer service ids without dots, such as `logger_audit`, because some containers treat dots as path separators.

## Channels

Channels define the Monolog logger name and the handler/processor stack.

```php
<?php

use Sirix\Monolog\Enum\ConfigKey;

return [
    ConfigKey::Root->value => [
        ConfigKey::Channels->value => [
            'default' => [
                ConfigKey::Name->value => 'app',
                ConfigKey::Handlers->value => ['stream'],
                ConfigKey::Processors->value => ['psr_message'],
            ],
        ],
    ],
];
```

Required keys:

- `handlers`: non-empty list of handler ids

Optional keys:

- `name`: logger/channel name, defaults to the channel id
- `processors`: list of processor ids, defaults to `[]`

## Handlers

Handlers are keyed by local ids. A channel references these ids.

```php
<?php

use Monolog\Level;
use Sirix\Monolog\Enum\ConfigKey;
use Sirix\Monolog\Enum\HandlerType;

return [
    ConfigKey::Root->value => [
        ConfigKey::Handlers->value => [
            'stream' => [
                ConfigKey::Type->value => HandlerType::Stream,
                ConfigKey::Formatter->value => 'line',
                ConfigKey::Processors->value => ['uid'],
                ConfigKey::Options->value => [
                    'stream' => 'php://stderr',
                    'level' => Level::Debug,
                ],
            ],
        ],
    ],
];
```

Required keys:

- `type`: a `HandlerType` enum case or non-empty type string

Optional keys:

- `options`: handler-specific options, defaults to `[]`
- `formatter`: formatter id
- `processors`: handler-local processor ids, defaults to `[]`

## Formatters

```php
<?php

use Sirix\Monolog\Enum\ConfigKey;
use Sirix\Monolog\Enum\FormatterType;

return [
    ConfigKey::Root->value => [
        ConfigKey::Formatters->value => [
            'line' => [
                ConfigKey::Type->value => FormatterType::Line,
                ConfigKey::Options->value => [
                    'format' => "%message%\n",
                    'date_format' => 'c',
                ],
            ],
        ],
    ],
];
```

## Processors

```php
<?php

use Sirix\Monolog\Enum\ConfigKey;
use Sirix\Monolog\Enum\ProcessorType;

return [
    ConfigKey::Root->value => [
        ConfigKey::Processors->value => [
            'uid' => [
                ConfigKey::Type->value => ProcessorType::Uid,
                ConfigKey::Options->value => [
                    'length' => 12,
                ],
            ],
        ],
    ],
];
```

## Custom factory maps

Register custom type strings by mapping them to factory class names.

```php
<?php

use App\Logging\AuditHandlerFactory;
use App\Logging\CryptoJsonFormatterFactory;
use Sirix\Monolog\Enum\ConfigKey;

return [
    ConfigKey::Root->value => [
        ConfigKey::HandlerFactories->value => [
            'audit' => AuditHandlerFactory::class,
        ],
        ConfigKey::FormatterFactories->value => [
            'crypto_json' => CryptoJsonFormatterFactory::class,
        ],
    ],
];
```

The configured type string is then used in the matching definition section, for example `ConfigKey::Type->value => 'crypto_json'` under `ConfigKey::Formatters`.

Factory classes must exist and implement the matching strict interface:

- `Sirix\Monolog\Handler\HandlerFactoryInterface`
- `Sirix\Monolog\Formatter\FormatterFactoryInterface`
- `Sirix\Monolog\Processor\ProcessorFactoryInterface`

## Type safety

The new configuration reader is strict:

- invalid scalar types are rejected instead of coerced
- missing required values fail early
- references to unknown handlers, formatters, or processors fail while reading config
- enum values can be provided as enum cases or valid backed values

Use `snake_case` option names for all built-in factories.
