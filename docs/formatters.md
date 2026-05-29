# Formatters

Built-in formatters are configured under `monolog.formatters`. Formatter option names use `snake_case`.

## `line`

Creates `Monolog\Formatter\LineFormatter`.

Options:

- `format` optional
- `date_format` optional
- `allow_inline_line_breaks` default: `false`
- `ignore_empty_context_and_extra` default: `false`

## `json`

Creates `Monolog\Formatter\JsonFormatter`.

Options:

- `batch_mode` default: `JsonFormatter::BATCH_MODE_JSON`
- `append_newline` default: `true`
- `ignore_empty_context_and_extra` default: `false`
- `include_stacktraces` default: `false`

`batch_mode` must be `JsonFormatter::BATCH_MODE_JSON` or `JsonFormatter::BATCH_MODE_NEWLINES`.

## `html`

Creates `Monolog\Formatter\HtmlFormatter`.

Options:

- `date_format` optional

## `normalizer`

Creates `Monolog\Formatter\NormalizerFormatter`.

Options:

- `date_format` optional

## `scalar`

Creates `Monolog\Formatter\ScalarFormatter`.

Options:

- `date_format` optional

## `logstash`

Creates `Monolog\Formatter\LogstashFormatter`.

Options:

- `application_name` default: `''`
- `system_name` optional
- `extra_key` default: `extra`
- `context_key` default: `context`

## `wildfire`

Creates `Monolog\Formatter\WildfireFormatter`.

Options:

- `date_format` optional

## `chrome_php`

Creates `Monolog\Formatter\ChromePHPFormatter`.

No options.

## `gelf`

Creates `Monolog\Formatter\GelfMessageFormatter`.

Options:

- `system_name` optional
- `extra_prefix` optional
- `context_prefix` default: `ctxt_`
- `max_length` optional

Requires `graylog2/gelf-php` when the formatter is instantiated.

## `elastica`

Creates `Monolog\Formatter\ElasticaFormatter`.

Options:

- `index` required
- `document_type` optional

Requires `ruflin/elastica` when the formatter is instantiated.

## `loggly`

Creates `Monolog\Formatter\LogglyFormatter`.

Options:

- `batch_mode` default: `LogglyFormatter::BATCH_MODE_NEWLINES`
- `append_newline` default: `false`

`batch_mode` must be a valid JSON formatter batch mode.

## `flowdock`

Creates `Monolog\Formatter\FlowdockFormatter`.

Options:

- `source` required
- `source_email` required

## `mongo_db`

Creates `Monolog\Formatter\MongoDBFormatter`.

Options:

- `max_nesting_level` default: `3`
- `exception_trace_as_string` default: `true`

Requires `mongodb/mongodb` when BSON date formatting is used.

## `logmatic`

Creates `Monolog\Formatter\LogmaticFormatter`.

Options:

- `batch_mode` default: `JsonFormatter::BATCH_MODE_JSON`
- `append_newline` default: `true`
- `hostname` optional
- `app_name` optional

`batch_mode` must be a valid JSON formatter batch mode.
