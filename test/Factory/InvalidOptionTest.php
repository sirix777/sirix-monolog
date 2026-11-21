<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Factory;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\ConfigProvider;
use Sirix\Monolog\Enum\ConfigKey as C;
use Sirix\Monolog\Enum\FormatterType;
use Sirix\Monolog\Enum\HandlerType;
use Sirix\Monolog\Enum\ProcessorType;
use Sirix\Monolog\Exception\InvalidConfigException;
use Sirix\Monolog\Registry\FormatterRegistry;
use Sirix\Monolog\Registry\HandlerRegistry;
use Sirix\Monolog\Registry\ProcessorRegistry;
use Sirix\Test\Monolog\Support\ArrayContainer;

final class InvalidOptionTest extends TestCase
{
    /**
     * @param array<string, mixed> $options
     */
    #[DataProvider('invalidHandlerOptionProvider')]
    public function testInvalidHandlerOptionFails(HandlerType $handlerType, array $options, string $message): void
    {
        $container = $this->container(handlers: [
            'main' => [
                C::Type->value    => $handlerType,
                C::Options->value => $options,
            ],
        ]);

        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage($message);

        $container->get(HandlerRegistry::class)->get('main');
    }

    /**
     * @return iterable<string, array{HandlerType, array<string, mixed>, string}>
     */
    public static function invalidHandlerOptionProvider(): iterable
    {
        $socket = [
            'connection_string' => 'udp://127.0.0.1:514',
        ];

        yield 'socket timeout' => [HandlerType::Socket, [
            ...$socket,
            'timeout' => 'fast',
        ], "Socket handler option 'timeout' must be a float or int."];

        yield 'socket connection_timeout' => [
            HandlerType::Socket,
            [
                ...$socket,
                'connection_timeout' => 'fast',
            ],
            "Socket handler option 'connection_timeout' must be a float, int, or null.",
        ];

        yield 'socket chunk_size' => [HandlerType::Socket, [
            ...$socket,
            'chunk_size' => 1.5,
        ], "Socket handler option 'chunk_size' must be an int or null."];

        $telegram = [
            'api_key' => '123456:test-token',
            'channel' => '-1001234567890',
        ];

        yield 'telegram_bot disable_notification' => [
            HandlerType::TelegramBot,
            [
                ...$telegram,
                'disable_notification' => 'yes',
            ],
            "Telegram bot handler option 'disable_notification' must be a bool or null.",
        ];

        yield 'telegram_bot topic' => [HandlerType::TelegramBot, [
            ...$telegram,
            'topic' => '1',
        ], "Telegram bot handler option 'topic' must be an int or null."];

        $mailer        = [
            'subject' => 'Log',
            'from'    => 'logs@example.com',
        ];
        $mailerMessage = "Native mailer handler option 'to' must be a non-empty string or list of non-empty strings.";

        yield 'native_mailer to with non-string item' => [HandlerType::NativeMailer, [
            ...$mailer,
            'to' => [1],
        ], $mailerMessage];

        yield 'native_mailer to with empty item' => [HandlerType::NativeMailer, [
            ...$mailer,
            'to' => [' '],
        ], $mailerMessage];

        yield 'native_mailer empty to list' => [HandlerType::NativeMailer, [
            ...$mailer,
            'to' => [],
        ], $mailerMessage];

        yield 'native_mailer blank to string' => [HandlerType::NativeMailer, [
            ...$mailer,
            'to' => ' ',
        ], $mailerMessage];

        yield 'stream file_permission' => [
            HandlerType::Stream,
            [
                'stream'          => 'php://memory',
                'file_permission' => '0644',
            ],
            "Stream handler option 'file_permission' must be an int or null.",
        ];

        yield 'rotating_file file_permission' => [
            HandlerType::RotatingFile,
            [
                'filename'        => '/tmp/app.log',
                'file_permission' => '0644',
            ],
            "Rotating file handler option 'file_permission' must be an int or null.",
        ];

        yield 'syslog facility' => [HandlerType::Syslog, [
            'ident'    => 'app',
            'facility' => 1.5,
        ], 'Syslog handler option "facility" must be an int or string.'];

        yield 'syslog_udp facility' => [
            HandlerType::SyslogUdp,
            [
                'host'     => '127.0.0.1',
                'facility' => 1.5,
            ],
            'Syslog UDP handler option "facility" must be an int or string.',
        ];

        yield 'syslog_udp rfc' => [
            HandlerType::SyslogUdp,
            [
                'host' => '127.0.0.1',
                'rfc'  => 99,
            ],
            'Syslog UDP handler option "rfc" must be a valid SyslogUdpHandler RFC constant.',
        ];

        yield 'process timeout' => [HandlerType::Process, [
            'command' => 'cat',
            'timeout' => 'slow',
        ], 'Process handler option "timeout" must be a float or int.'];

        yield 'sampling factor' => [HandlerType::Sampling, [
            'factor'  => 0,
            'handler' => 'unused',
        ], 'Factor is missing'];

        yield 'error_log message_type' => [HandlerType::ErrorLog, [
            'message_type' => 99,
        ], 'Error log handler option "message_type" is not supported.'];

        $slack = [
            'token'   => 'token',
            'channel' => '#logs',
        ];

        yield 'slack timeout' => [HandlerType::Slack, [
            ...$slack,
            'timeout' => 'fast',
        ], "Slack handler option 'timeout' must be a float or int."];

        yield 'slack connection_timeout' => [
            HandlerType::Slack,
            [
                ...$slack,
                'connection_timeout' => 'fast',
            ],
            "Slack handler option 'connection_timeout' must be a float, int, or null.",
        ];

        yield 'slack chunk_size' => [HandlerType::Slack, [
            ...$slack,
            'chunk_size' => '1',
        ], "Slack handler option 'chunk_size' must be an int or null."];

        $pushoverMessage = "Pushover handler option 'users' must be a non-empty string or list of non-empty strings.";

        yield 'pushover users with non-string item' => [HandlerType::Pushover, [
            'token' => 'token',
            'users' => [1],
        ], $pushoverMessage];

        yield 'pushover users with empty item' => [HandlerType::Pushover, [
            'token' => 'token',
            'users' => [''],
        ], $pushoverMessage];

        yield 'pushover missing users' => [HandlerType::Pushover, [
            'token' => 'token',
        ], $pushoverMessage];
    }

    #[DataProvider('invalidBatchModeProvider')]
    public function testInvalidFormatterBatchModeFails(FormatterType $formatterType, string $message): void
    {
        $container = $this->container(formatters: [
            'main' => [
                C::Type->value    => $formatterType,
                C::Options->value => [
                    'batch_mode' => 99,
                ],
            ],
        ]);

        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage($message);

        $container->get(FormatterRegistry::class)->get('main');
    }

    /**
     * @return iterable<string, array{FormatterType, string}>
     */
    public static function invalidBatchModeProvider(): iterable
    {
        yield 'json' => [FormatterType::Json, 'Json formatter option "batch_mode" must be a valid JsonFormatter batch mode.'];

        yield 'loggly' => [FormatterType::Loggly, 'Loggly formatter option "batch_mode" must be a valid JsonFormatter batch mode.'];

        yield 'logmatic' => [FormatterType::Logmatic, 'Logmatic formatter option "batch_mode" must be a valid JsonFormatter batch mode.'];

        yield 'google_cloud_logging' => [
            FormatterType::GoogleCloudLogging,
            'Google Cloud Logging formatter option "batch_mode" must be a valid JsonFormatter batch mode.',
        ];
    }

    /**
     * @param array<string, mixed> $options
     */
    #[DataProvider('invalidProcessorOptionProvider')]
    public function testInvalidProcessorOptionFails(ProcessorType $processorType, array $options, string $message): void
    {
        $container = $this->container(processors: [
            'main' => [
                C::Type->value    => $processorType,
                C::Options->value => $options,
            ],
        ]);

        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage($message);

        $container->get(ProcessorRegistry::class)->get('main');
    }

    /**
     * @return iterable<string, array{ProcessorType, array<string, mixed>, string}>
     */
    public static function invalidProcessorOptionProvider(): iterable
    {
        yield 'uid length too small' => [ProcessorType::Uid, [
            'length' => 0,
        ], 'Uid processor option "length" must be between 1 and 32.'];

        yield 'uid length too large' => [ProcessorType::Uid, [
            'length' => 33,
        ], 'Uid processor option "length" must be between 1 and 32.'];

        yield 'load_average avg_system_load' => [
            ProcessorType::LoadAverage,
            [
                'avg_system_load' => 5,
            ],
            'Load average processor option "avg_system_load" must be one of 0, 1, or 2.',
        ];

        yield 'web server_data' => [
            ProcessorType::Web,
            [
                'server_data' => 123,
            ],
            'Web processor option "server_data" must be an array, ArrayAccess, service id, or null.',
        ];

        yield 'web extra_fields' => [ProcessorType::Web, [
            'extra_fields' => 'url',
        ], "Web processor option 'extra_fields' must be an array or null."];
    }

    /**
     * @param array<string, array<string, mixed>> $handlers
     * @param array<string, array<string, mixed>> $formatters
     * @param array<string, array<string, mixed>> $processors
     */
    private function container(array $handlers = [], array $formatters = [], array $processors = []): ArrayContainer
    {
        return ArrayContainer::fromConfigProvider([
            C::Root->value => [
                C::Handlers->value   => [
                    'default' => [
                        C::Type->value => HandlerType::Noop,
                    ],
                    ...$handlers,
                ],
                C::Formatters->value => $formatters,
                C::Processors->value => $processors,
            ],
        ], (new ConfigProvider())());
    }
}
