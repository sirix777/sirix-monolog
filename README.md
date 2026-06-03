# Mezzio Monolog Factory

[![Latest Stable Version](http://poser.pugx.org/sirix/monolog/v)](https://packagist.org/packages/sirix/monolog) [![Total Downloads](http://poser.pugx.org/sirix/monolog/downloads)](https://packagist.org/packages/sirix/monolog) [![License](http://poser.pugx.org/sirix/monolog/license)](https://packagist.org/packages/sirix/monolog) [![PHP Version Require](http://poser.pugx.org/sirix/monolog/require/php)](https://packagist.org/packages/sirix/monolog)

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

The provider registers `logger`, `Monolog\Logger`, and `Psr\Log\LoggerInterface` for the default logger service.

## Default behavior

If no `monolog` configuration is provided, the package still exposes a safe default logger. The default services point to the `default` channel, that channel uses the Monolog name `app`, and it writes to a `noop` handler. Requesting `Psr\Log\LoggerInterface` is therefore safe even before application logging is configured; records are discarded until you define real handlers.

Defaults are applied per missing section. For example, a basic application can configure `channels` and `handlers` while omitting `logger_services`; the default `logger` service map will continue to point to the `default` channel.

## Minimal configuration

Create `config/autoload/monolog.global.php`. The basic configuration does not need a `logger_services` section because `logger`, `Monolog\Logger`, and `Psr\Log\LoggerInterface` are registered for the `default` channel automatically:

```php
<?php

use Monolog\Level;
use Sirix\Monolog\Enum\ConfigKey;
use Sirix\Monolog\Enum\FormatterType;
use Sirix\Monolog\Enum\HandlerType;
use Sirix\Monolog\Enum\ProcessorType;

return [
    ConfigKey::Root->value => [
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
            'logger' => 'default',
            'logger_audit' => 'audit',
            'logger_crypto_transaction' => [
                ConfigKey::Channel->value => 'audit',
                ConfigKey::Name->value => 'CryptoTransactionService',
            ],
        ],
        ConfigKey::Channels->value => [
            'default' => [
                ConfigKey::Name->value => 'app',
                ConfigKey::Handlers->value => ['app_file'],
            ],
            'audit' => [
                ConfigKey::Name->value => 'audit',
                ConfigKey::Handlers->value => ['audit_file'],
            ],
        ],
        ConfigKey::Handlers->value => [
            'app_file' => [
                ConfigKey::Type->value => HandlerType::Stream,
                ConfigKey::Options->value => [
                    'stream' => 'data/log/app.log',
                ],
            ],
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

Providing `logger_services` replaces the default service map for that section, so keep the `logger` entry when the default logger service should remain available. With the provided `ConfigProvider`, `Monolog\Logger` and `Psr\Log\LoggerInterface` are aliases to `logger`; keep the class-name entries too only if your container resolves those ids through `LoggerFactory` directly instead of aliases.

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
- `mandrill`
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

## Runtime notes

Configured loggers, handlers, formatters, and processors are cached for the container lifetime. In long-running workers, call `Logger::reset()` between jobs/messages so Monolog can flush buffers and release or reopen stateful resources.

Handlers are shared by handler id. If multiple channels reference the same handler id, they share the same handler instance and state. Use separate handler ids when buffered, deduplicated, or otherwise stateful handlers must be isolated.

Handler and processor order is preserved as written in configuration. This matters for `bubble`, wrapper handlers, filters, and processor chains.

A handler-local `formatter` requires a Monolog handler that supports formatters. Handler-local `processors` require a processable handler. Unsupported combinations fail fast with a configuration exception instead of being ignored.

For stream handlers, `file_permission` defaults to Monolog's default when omitted or set to `null`, so file permissions remain controlled by your process `umask`. `use_locking` defaults to `false`, matching Monolog. Enable it only when multiple processes write to the same file and you want `flock()` around each write.

## Optional integrations

Many built-in handlers and formatters wrap Monolog integrations that require optional packages or PHP extensions. Install only the dependencies you use; Composer `suggest` lists the packages/extensions required by each optional integration. If an optional dependency is missing, the related factory fails with a configuration error when that handler, formatter, or processor is built.

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
- [Official Monolog documentation](https://seldaek.github.io/monolog/)

## Notes for 2.x

Version 2.x is a breaking release. The legacy `MonologFactory`, `ChannelChanger`, service managers, mappers, container-aware traits, and `__invoke(array $options)` factory API were removed. Use `ConfigProvider`, enum-backed configuration, and the strict factory interfaces instead.
