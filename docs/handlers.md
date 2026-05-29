# Handlers

Built-in handlers are configured under `monolog.handlers`. Handler option names use `snake_case`.

Common optional options where supported:

- `level`: `Monolog\Level`, level name, or level value depending on enum parsing
- `bubble`: bool

## Basic handlers

### `stream`

Creates `Monolog\Handler\StreamHandler`.

Options:

- `stream` required: stream resource, path, or container service id resolving to a stream
- `level` default: `Level::Debug`
- `bubble` default: `true`
- `file_permission` default: `0o644`
- `use_locking` default: `true`

### `rotating_file`

Creates `Monolog\Handler\RotatingFileHandler`.

Options:

- `filename` required
- `max_files` default: `0`
- `level` default: `Level::Debug`
- `bubble` default: `true`
- `file_permission` default: Monolog default when omitted/null
- `use_locking` default: `false`
- `date_format` default: `RotatingFileHandler::FILE_PER_DAY`
- `filename_format` default: `{filename}-{date}`

### `syslog`

Creates `Monolog\Handler\SyslogHandler`.

Options:

- `ident` required
- `facility` default: `LOG_USER`
- `level` default: `Level::Debug`
- `bubble` default: `true`
- `log_opts` default: `LOG_PID`

### `error_log`

Creates `Monolog\Handler\ErrorLogHandler`.

Options:

- `message_type` default: `ErrorLogHandler::OPERATING_SYSTEM`
- `level` default: `Level::Debug`
- `bubble` default: `true`
- `expand_newlines` default: `false`

### `process`

Creates `Monolog\Handler\ProcessHandler`.

Options:

- `command` required
- `level` default: `Level::Debug`
- `bubble` default: `true`
- `cwd` optional
- `timeout` default: `1.0`

### `psr`

Creates `Monolog\Handler\PsrHandler` and forwards records to another PSR-3 logger from the container.

Options:

- `logger` required: container service id resolving to `Psr\Log\LoggerInterface`
- `level` default: `Level::Debug`
- `bubble` default: `true`
- `include_extra` default: `false`

### `test`

Creates `Monolog\Handler\TestHandler`.

Options:

- `level` default: `Level::Debug`
- `bubble` default: `true`

### `null`

Creates `Monolog\Handler\NullHandler`.

Options:

- `level` default: `Level::Debug`

### `noop`

Creates `Monolog\Handler\NoopHandler`.

No options.

## Wrapper handlers

Wrapper handlers reference other configured handler ids. Circular references fail predictably.

### `group`

Creates `Monolog\Handler\GroupHandler`.

Options:

- `handlers` required: non-empty list of handler ids
- `bubble` default: `true`

### `what_failure_group`

Creates `Monolog\Handler\WhatFailureGroupHandler`.

Options:

- `handlers` required: non-empty list of handler ids
- `bubble` default: `true`

### `fallback_group`

Creates `Monolog\Handler\FallbackGroupHandler`.

Options:

- `handlers` required: non-empty list of handler ids
- `bubble` default: `true`

### `buffer`

Creates `Monolog\Handler\BufferHandler`.

Options:

- `handler` required: wrapped handler id
- `buffer_limit` default: `0`
- `level` default: `Level::Debug`
- `bubble` default: `true`
- `flush_on_overflow` default: `false`

### `filter`

Creates `Monolog\Handler\FilterHandler`.

Options:

- `handler` required: wrapped handler id
- `min_level_or_list` default: `Level::Debug`
- `max_level` default: `Level::Emergency`
- `bubble` default: `true`

### `fingers_crossed`

Creates `Monolog\Handler\FingersCrossedHandler`.

Options:

- `handler` required: wrapped handler id
- `activation_strategy` optional: level or container service id resolving to `ActivationStrategyInterface`
- `buffer_size` default: `0`
- `bubble` default: `true`
- `stop_buffering` default: `true`
- `passthru_level` optional

### `sampling`

Creates `Monolog\Handler\SamplingHandler`.

Options:

- `handler` required: wrapped handler id
- `factor` required: integer greater than zero

### `deduplication`

Creates `Monolog\Handler\DeduplicationHandler`.

Options:

- `handler` required: wrapped handler id
- `deduplication_store` optional
- `deduplication_level` default: `Level::Error`
- `time` default: `60`
- `bubble` default: `true`

### `overflow`

Creates `Monolog\Handler\OverflowHandler`.

Options:

- `handler` required: wrapped handler id
- `threshold_map` default: all levels `0`
- `level` default: `Level::Debug`
- `bubble` default: `true`

`threshold_map` keys are `debug`, `info`, `notice`, `warning`, `error`, `critical`, `alert`, and `emergency`.
