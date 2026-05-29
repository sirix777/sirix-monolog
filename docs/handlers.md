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

### `browser_console`

Creates `Monolog\Handler\BrowserConsoleHandler`.

Options:

- `level` default: `Level::Debug`
- `bubble` default: `true`

### `chrome_php`

Creates `Monolog\Handler\ChromePHPHandler`.

Options:

- `level` default: `Level::Debug`
- `bubble` default: `true`

### `fire_php`

Creates `Monolog\Handler\FirePHPHandler`.

Options:

- `level` default: `Level::Debug`
- `bubble` default: `true`

### `native_mailer`

Creates `Monolog\Handler\NativeMailerHandler`.

Options:

- `to` required: non-empty string or list of non-empty strings
- `subject` required
- `from` required
- `level` default: `Level::Error`
- `bubble` default: `true`
- `max_column_width` default: `70`
- `headers` optional: non-empty string or list of non-empty strings
- `parameters` optional: non-empty string or list of non-empty strings
- `content_type` optional
- `encoding` optional

### `process`

Creates `Monolog\Handler\ProcessHandler`.

Options:

- `command` required
- `level` default: `Level::Debug`
- `bubble` default: `true`
- `cwd` optional
- `timeout` default: `1.0`

### `socket`

Creates `Monolog\Handler\SocketHandler`.

Options:

- `connection_string` required
- `level` default: `Level::Debug`
- `bubble` default: `true`
- `persistent` default: `false`
- `timeout` default: `0.0`
- `writing_timeout` default: `10.0`
- `connection_timeout` optional float/int or null
- `chunk_size` optional int or null

### `syslog_udp`

Creates `Monolog\Handler\SyslogUdpHandler`.

Options:

- `host` required
- `port` default: `514`
- `facility` default: `LOG_USER`
- `level` default: `Level::Debug`
- `bubble` default: `true`
- `ident` default: `php`
- `rfc` default: `SyslogUdpHandler::RFC5424`

Requires `ext-sockets` when the handler is instantiated.

### `telegram_bot`

Creates `Monolog\Handler\TelegramBotHandler`.

Options:

- `api_key` required
- `channel` required
- `level` default: `Level::Debug`
- `bubble` default: `true`
- `parse_mode` optional: `HTML`, `MarkdownV2`, or `Markdown`
- `disable_web_page_preview` optional bool or null
- `disable_notification` optional bool or null
- `split_long_messages` default: `false`
- `delay_between_messages` default: `false`
- `topic` optional int or null

Requires `ext-curl` when the handler is instantiated.

### `amqp`

Creates `Monolog\Handler\AmqpHandler`.

Options:

- `exchange` required: `AMQPExchange`, `PhpAmqpLib\Channel\AMQPChannel`, or service id resolving to one
- `exchange_name` optional
- `level` default: `Level::Debug`
- `bubble` default: `true`

### `couch_db`

Creates `Monolog\Handler\CouchDBHandler`.

Options:

- `connection` default: `[]`
- `level` default: `Level::Debug`
- `bubble` default: `true`

### `cube`

Creates `Monolog\Handler\CubeHandler`.

Options:

- `url` required
- `level` default: `Level::Debug`
- `bubble` default: `true`

### `doctrine_couch_db`

Creates `Monolog\Handler\DoctrineCouchDBHandler`.

Options:

- `client` required: `Doctrine\CouchDB\CouchDBClient` or service id resolving to it
- `level` default: `Level::Debug`
- `bubble` default: `true`

### `dynamo_db`

Creates `Monolog\Handler\DynamoDbHandler`.

Options:

- `client` required: `Aws\DynamoDb\DynamoDbClient` or service id resolving to it
- `table` required
- `level` default: `Level::Debug`
- `bubble` default: `true`

### `elastica`

Creates `Monolog\Handler\ElasticaHandler`.

Options:

- `client` required: `Elastica\Client` or service id resolving to it
- `handler_options` default: `[]`
- `level` default: `Level::Debug`
- `bubble` default: `true`

### `elasticsearch`

Creates `Monolog\Handler\ElasticsearchHandler`.

Options:

- `client` required: `Elasticsearch\Client`, `Elastic\Elasticsearch\Client`, or service id resolving to one
- `handler_options` default: `[]`
- `level` default: `Level::Debug`
- `bubble` default: `true`

### `fleep_hook`

Creates `Monolog\Handler\FleepHookHandler`.

Options:

- `token` required
- `level` default: `Level::Debug`
- `bubble` default: `true`
- `persistent` default: `false`
- `timeout` default: `0.0`
- `writing_timeout` default: `10.0`
- `connection_timeout` optional float/int or null
- `chunk_size` optional int or null

Requires `ext-openssl` when the handler is instantiated.

### `flowdock`

Creates `Monolog\Handler\FlowdockHandler`.

Options:

- `api_token` required
- `level` default: `Level::Debug`
- `bubble` default: `true`
- `persistent` default: `false`
- `timeout` default: `0.0`
- `writing_timeout` default: `10.0`
- `connection_timeout` optional float/int or null
- `chunk_size` optional int or null

Requires `ext-openssl` when the handler is instantiated.

### `gelf`

Creates `Monolog\Handler\GelfHandler`.

Options:

- `publisher` required: `Gelf\PublisherInterface` or service id resolving to it
- `level` default: `Level::Debug`
- `bubble` default: `true`

### `ifttt`

Creates `Monolog\Handler\IFTTTHandler`.

Options:

- `event_name` required
- `secret_key` required
- `level` default: `Level::Error`
- `bubble` default: `true`

Requires `ext-curl` when the handler is instantiated.

### `insight_ops`

Creates `Monolog\Handler\InsightOpsHandler`.

Options:

- `token` required
- `region` default: `us`
- `use_ssl` default: `true`
- `level` default: `Level::Debug`
- `bubble` default: `true`
- `persistent` default: `false`
- `timeout` default: `0.0`
- `writing_timeout` default: `10.0`
- `connection_timeout` optional float/int or null
- `chunk_size` optional int or null

Requires `ext-openssl` when `use_ssl` is `true`.

### `log_entries`

Creates `Monolog\Handler\LogEntriesHandler`.

Options:

- `token` required
- `use_ssl` default: `true`
- `host` default: `data.logentries.com`
- `level` default: `Level::Debug`
- `bubble` default: `true`
- `persistent` default: `false`
- `timeout` default: `0.0`
- `writing_timeout` default: `10.0`
- `connection_timeout` optional float/int or null
- `chunk_size` optional int or null

Requires `ext-openssl` when `use_ssl` is `true`.

### `loggly`

Creates `Monolog\Handler\LogglyHandler`.

Options:

- `token` required
- `tag` optional: non-empty string or list of non-empty strings
- `level` default: `Level::Debug`
- `bubble` default: `true`

Requires `ext-curl` when the handler is instantiated.

### `logmatic`

Creates `Monolog\Handler\LogmaticHandler`.

Options:

- `token` required
- `hostname` default: `''`
- `app_name` default: `''`
- `use_ssl` default: `true`
- `level` default: `Level::Debug`
- `bubble` default: `true`
- `persistent` default: `false`
- `timeout` default: `0.0`
- `writing_timeout` default: `10.0`
- `connection_timeout` optional float/int or null
- `chunk_size` optional int or null

Requires `ext-openssl` when `use_ssl` is `true`.

### `mandrill`

Creates `Monolog\Handler\MandrillHandler`.

Options:

- `api_key` required
- `message` required: callable, `Swift_Message`, or service id resolving to one
- `level` default: `Level::Error`
- `bubble` default: `true`

Requires SwiftMailer classes when instantiated.

### `mongo_db`

Creates `Monolog\Handler\MongoDBHandler`.

Options:

- `mongodb` required: `MongoDB\Client`, `MongoDB\Driver\Manager`, or service id resolving to one
- `database` required
- `collection` required
- `level` default: `Level::Debug`
- `bubble` default: `true`

### `new_relic`

Creates `Monolog\Handler\NewRelicHandler`.

Options:

- `level` default: `Level::Error`
- `bubble` default: `true`
- `app_name` optional
- `explode_arrays` default: `false`
- `transaction_name` optional

The New Relic extension is required when handling records.

### `php_console`

Creates `Monolog\Handler\PHPConsoleHandler`.

Options:

- `php_console_options` default: `[]`
- `connector` optional: `PhpConsole\Connector` or service id resolving to it
- `level` default: `Level::Debug`
- `bubble` default: `true`

### `pushover`

Creates `Monolog\Handler\PushoverHandler`.

Options:

- `token` required
- `users` required: non-empty string or list of non-empty strings
- `title` optional
- `level` default: `Level::Critical`
- `bubble` default: `true`
- `use_ssl` default: `true`
- `high_priority_level` default: `Level::Critical`
- `emergency_level` default: `Level::Emergency`
- `retry` default: `30`
- `expire` default: `25200`
- `persistent` default: `false`
- `timeout` default: `0.0`
- `writing_timeout` default: `10.0`
- `connection_timeout` optional float/int or null
- `chunk_size` optional int or null
- `use_formatted_message` default: `false`

### `redis`

Creates `Monolog\Handler\RedisHandler`.

Options:

- `redis` required: `Predis\Client`, `Redis`, or service id resolving to one
- `key` required
- `level` default: `Level::Debug`
- `bubble` default: `true`
- `cap_size` default: `0`

### `redis_pub_sub`

Creates `Monolog\Handler\RedisPubSubHandler`.

Options:

- `redis` required: `Predis\Client`, `Redis`, or service id resolving to one
- `key` required
- `level` default: `Level::Debug`
- `bubble` default: `true`

### `rollbar`

Creates `Monolog\Handler\RollbarHandler`.

Options:

- `rollbar_logger` required: `Rollbar\RollbarLogger` or service id resolving to it
- `level` default: `Level::Error`
- `bubble` default: `true`

### `send_grid`

Creates `Monolog\Handler\SendGridHandler`.

Options:

- `api_user` optional
- `api_key` required
- `from` required
- `to` required: non-empty string or list of non-empty strings
- `subject` required
- `level` default: `Level::Error`
- `bubble` default: `true`
- `api_host` default: `api.sendgrid.com`

Requires `ext-curl` when the handler is instantiated.

### `slack`

Creates `Monolog\Handler\SlackHandler`.

Options:

- `token` required
- `channel` required
- `username` optional
- `use_attachment` default: `true`
- `icon_emoji` optional
- `level` default: `Level::Critical`
- `bubble` default: `true`
- `use_short_attachment` default: `false`
- `include_context_and_extra` default: `false`
- `exclude_fields` default: `[]`
- `persistent` default: `false`
- `timeout` default: `0.0`
- `writing_timeout` default: `10.0`
- `connection_timeout` optional float/int or null
- `chunk_size` optional int or null

Requires `ext-openssl` when the handler is instantiated.

### `slack_webhook`

Creates `Monolog\Handler\SlackWebhookHandler`.

Options:

- `webhook_url` required
- `channel` optional
- `username` optional
- `use_attachment` default: `true`
- `icon_emoji` optional
- `use_short_attachment` default: `false`
- `include_context_and_extra` default: `false`
- `level` default: `Level::Critical`
- `bubble` default: `true`
- `exclude_fields` default: `[]`

Requires `ext-curl` when the handler is instantiated.

### `sqs`

Creates `Monolog\Handler\SqsHandler`.

Options:

- `client` required: `Aws\Sqs\SqsClient` or service id resolving to it
- `queue_url` required
- `level` default: `Level::Debug`
- `bubble` default: `true`

### `symfony_mailer`

Creates `Monolog\Handler\SymfonyMailerHandler`.

Options:

- `mailer` required: `Symfony\Component\Mailer\MailerInterface`, `Symfony\Component\Mailer\Transport\TransportInterface`, or service id resolving to one
- `email` required: `Symfony\Component\Mime\Email`, callable, or service id resolving to one
- `level` default: `Level::Error`
- `bubble` default: `true`

### `zend_monitor`

Creates `Monolog\Handler\ZendMonitorHandler`.

Options:

- `level` default: `Level::Debug`
- `bubble` default: `true`

Requires Zend Server with Zend Monitor enabled when instantiated.

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
