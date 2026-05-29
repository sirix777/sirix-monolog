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

In Mezzio applications that use `laminas/laminas-component-installer`, the provider is registered automatically during Composer install.

If your application does not use auto-discovery, add the provider to `config/config.php` manually:

```php
<?php

use Sirix\Monolog\ConfigProvider;

return [
    ConfigProvider::class,
    // ...
];
```

The provider registers `logger`, `Monolog\Logger`, and `Psr\Log\LoggerInterface` for the default logger service. Dotted service ids are intentionally avoided because some containers treat dots as path separators.

If resolving `logger` fails with a `ConfigAbstractFactory` error such as `Service dependencies config must exist and be an array`, the Monolog `ConfigProvider` was not merged into the application config, or another config entry overrides the `logger` factory. The effective dependency config must contain:

```php
'dependencies' => [
    'aliases' => [
        Monolog\Logger::class => 'logger',
        Psr\Log\LoggerInterface::class => 'logger',
    ],
    'factories' => [
        'logger' => Sirix\Monolog\Factory\LoggerFactory::class,
    ],
],
```

Do not register `logger` with `Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory` unless you provide its dependency list yourself.

## Minimal configuration

Create `config/autoload/monolog.global.php`:

```php
<?php

use Monolog\Level;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Sirix\Monolog\Enum\ConfigKey;
use Sirix\Monolog\Enum\FormatterType;
use Sirix\Monolog\Enum\HandlerType;
use Sirix\Monolog\Enum\ProcessorType;

return [
    ConfigKey::Root->value => [
        ConfigKey::LoggerServices->value => [
            'logger' => 'default',
            Logger::class => 'default',
            LoggerInterface::class => 'default',
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

Map any service id to a channel id with `logger_services`. Use a string value when the service should use the configured channel as-is. Use the array form when the service should reuse a configured channel stack but emit records with a different Monolog channel name.

```php
<?php

use Sirix\Monolog\Enum\ConfigKey;
use Sirix\Monolog\Enum\HandlerType;

return [
    ConfigKey::Root->value => [
        ConfigKey::LoggerServices->value => [
            'logger_audit' => 'audit',
            'logger_crypto_transaction' => [
                ConfigKey::Channel->value => 'audit',
                ConfigKey::Name->value => 'CryptoTransactionService',
            ],
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

Register additional logger services with `Sirix\Monolog\Factory\LoggerFactory` in your container if your framework does not auto-wire them from the dependency config.

In the example above, `logger_crypto_transaction` reuses the `audit` channel's handlers and processors but writes `CryptoTransactionService` into Monolog's record `channel` field.

## Built-in types

### Handlers

- `amqp`
- `browser_console`
- `buffer`
- `chrome_php`
- `couch_db`
- `cube`
- `deduplication`
- `doctrine_couch_db`
- `dynamo_db`
- `elastica`
- `elasticsearch`
- `error_log`
- `fallback_group`
- `filter`
- `fingers_crossed`
- `fire_php`
- `fleep_hook`
- `flowdock`
- `gelf`
- `group`
- `ifttt`
- `insight_ops`
- `log_entries`
- `loggly`
- `logmatic`
- `mongo_db`
- `native_mailer`
- `new_relic`
- `noop`
- `null`
- `overflow`
- `php_console`
- `process`
- `psr`
- `pushover`
- `redis`
- `redis_pub_sub`
- `rollbar`
- `rotating_file`
- `sampling`
- `send_grid`
- `slack`
- `slack_webhook`
- `socket`
- `sqs`
- `stream`
- `symfony_mailer`
- `syslog`
- `syslog_udp`
- `telegram_bot`
- `test`
- `what_failure_group`
- `zend_monitor`

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
- `elasticsearch`
- `fluentd`
- `google_cloud_logging`
- `loggly`
- `flowdock`
- `mongo_db`
- `logmatic`
- `syslog`

### Processors

- `psr_log_message`
- `closure_context`
- `git`
- `introspection`
- `load_average`
- `mercurial`
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
