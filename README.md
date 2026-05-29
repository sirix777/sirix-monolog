# Mezzio Monolog Factory

[![Latest Stable Version](http://poser.pugx.org/sirix/monolog/v)](https://packagist.org/packages/sirix/monolog)
[![Total Downloads](http://poser.pugx.org/sirix/monolog/downloads)](https://packagist.org/packages/sirix/monolog)
[![License](http://poser.pugx.org/sirix/monolog/license)](https://packagist.org/packages/sirix/monolog)
[![PHP Version Require](http://poser.pugx.org/sirix/monolog/require/php)](https://packagist.org/packages/sirix/monolog)

Explicit Mezzio Monolog factory integration built around PSR-11, strict configuration, and predictable factories.

## Requirements

- PHP 8.2+
- Monolog 3.x
- PSR-11 compatible container
- Mezzio config aggregation, or another container that can consume the provided factory map

## Installation

```bash
composer require sirix/monolog
```

## Register the config provider

For Mezzio, add the provider to `config/config.php`:

```php
<?php

use Sirix\Monolog\ConfigProvider;

return [
    ConfigProvider::class,
    // ...
];
```

The provider registers `Psr\Log\LoggerInterface` as an alias for `logger.default`.

## Minimal configuration

Create `config/autoload/monolog.global.php`:

```php
<?php

use Monolog\Level;
use Psr\Log\LoggerInterface;
use Sirix\Monolog\Enum\ConfigKey;
use Sirix\Monolog\Enum\FormatterType;
use Sirix\Monolog\Enum\HandlerType;
use Sirix\Monolog\Enum\ProcessorType;

return [
    ConfigKey::Root->value => [
        ConfigKey::LoggerServices->value => [
            LoggerInterface::class => 'default',
            'logger.default' => 'default',
        ],
        ConfigKey::Channels->value => [
            'default' => [
                ConfigKey::Name->value => 'app',
                ConfigKey::Handlers->value => ['stream'],
                ConfigKey::Processors->value => ['psr_message'],
            ],
        ],
        ConfigKey::Handlers->value => [
            'stream' => [
                ConfigKey::Type->value => HandlerType::Stream,
                ConfigKey::Formatter->value => 'line',
                ConfigKey::Options->value => [
                    'stream' => 'php://stderr',
                    'level' => Level::Debug,
                ],
            ],
        ],
        ConfigKey::Formatters->value => [
            'line' => [
                ConfigKey::Type->value => FormatterType::Line,
                ConfigKey::Options->value => [
                    'format' => "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n",
                    'date_format' => 'c',
                    'ignore_empty_context_and_extra' => true,
                ],
            ],
        ],
        ConfigKey::Processors->value => [
            'psr_message' => [
                ConfigKey::Type->value => ProcessorType::PsrLogMessage,
            ],
        ],
    ],
];
```

Use the logger from the container:

```php
<?php

use Psr\Log\LoggerInterface;

$logger = $container->get(LoggerInterface::class);
$logger->info('User {user_id} logged in', ['user_id' => 42]);
```

## Multiple logger services

Map any service id to a channel id with `logger_services`:

```php
<?php

use Sirix\Monolog\Enum\ConfigKey;
use Sirix\Monolog\Enum\HandlerType;

return [
    ConfigKey::Root->value => [
        ConfigKey::LoggerServices->value => [
            'logger.audit' => 'audit',
        ],
        ConfigKey::Channels->value => [
            'audit' => [
                ConfigKey::Name->value => 'audit',
                ConfigKey::Handlers->value => ['audit_file'],
            ],
        ],
        ConfigKey::Handlers->value => [
            'audit_file' => [
                ConfigKey::Type->value => HandlerType::Stream,
                ConfigKey::Options->value => [
                    'stream' => 'data/log/audit.log',
                ],
            ],
        ],
    ],
];
```

Register the additional logger service with `Sirix\Monolog\Factory\LoggerFactory` in your container if your framework does not auto-wire it from the dependency config.

## Built-in types

### Handlers

- `stream`
- `rotating_file`
- `syslog`
- `error_log`
- `process`
- `psr`
- `test`
- `null`
- `noop`
- `group`
- `what_failure_group`
- `fallback_group`
- `buffer`
- `filter`
- `fingers_crossed`
- `sampling`
- `deduplication`
- `overflow`

### Formatters

- `line`
- `json`
- `html`
- `normalizer`
- `scalar`
- `logstash`
- `wildfire`
- `chrome_php`
- `gelf`
- `elastica`
- `loggly`
- `flowdock`
- `mongo_db`
- `logmatic`

### Processors

- `psr_log_message`
- `introspection`
- `web`
- `memory_usage`
- `memory_peak_usage`
- `process_id`
- `uid`
- `hostname`
- `tags`
- `redactor`

## Custom factories

Register custom factory classes under `handler_factories`, `formatter_factories`, or `processor_factories`.

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

Custom factories must implement one of:

- `Sirix\Monolog\Handler\HandlerFactoryInterface`
- `Sirix\Monolog\Formatter\FormatterFactoryInterface`
- `Sirix\Monolog\Processor\ProcessorFactoryInterface`

## Documentation

- [Configuration](docs/configuration.md)
- [Handlers](docs/handlers.md)
- [Formatters](docs/formatters.md)
- [Processors](docs/processors.md)
- [Migration from 1.x](docs/migration-from-1.x.md)

## Notes for 2.x

Version 2.x is a breaking release. The legacy `MonologFactory`, `ChannelChanger`, service managers, mappers, container-aware traits, and `__invoke(array $options)` factory API were removed. Use `ConfigProvider`, enum-backed configuration, and the strict factory interfaces instead.
