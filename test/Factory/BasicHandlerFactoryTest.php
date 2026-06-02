<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Factory;

use const LOG_PID;
use const LOG_USER;

use Monolog\Handler\BrowserConsoleHandler;
use Monolog\Handler\ChromePHPHandler;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\NativeMailerHandler;
use Monolog\Handler\ProcessHandler;
use Monolog\Handler\PsrHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\SocketHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogHandler;
use Monolog\Handler\SyslogUdpHandler;
use Monolog\Handler\TelegramBotHandler;
use Monolog\Handler\TestHandler;
use Monolog\Level;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use ReflectionClass;
use Sirix\Monolog\ConfigProvider;
use Sirix\Monolog\Enum\ConfigKey as C;
use Sirix\Monolog\Enum\FormatterType;
use Sirix\Monolog\Enum\HandlerType;
use Sirix\Monolog\Exception\InvalidConfigException;
use Sirix\Monolog\Registry\HandlerRegistry;
use Sirix\Test\Monolog\Support\AppendMessageProcessorFactory;
use Sirix\Test\Monolog\Support\ArrayContainer;
use Sirix\Test\Monolog\Support\CollectingLogger;

use function extension_loaded;
use function sys_get_temp_dir;
use function tempnam;
use function unlink;

final class BasicHandlerFactoryTest extends TestCase
{
    public function testTestHandlerRecordsMessages(): void
    {
        $container = $this->container([
            'test' => [
                C::Type->value => HandlerType::Test,
            ],
        ], ['test']);

        $logger = $container->get(LoggerInterface::class);
        $this->assertInstanceOf(Logger::class, $logger);

        $logger->info('Test handler message');

        $handler = $container->get(HandlerRegistry::class)->get('test');
        $this->assertInstanceOf(TestHandler::class, $handler);
        $this->assertTrue($handler->hasInfoThatContains('Test handler message'));
    }

    public function testPsrHandlerProxiesToConfiguredLogger(): void
    {
        $collectingLogger = new CollectingLogger();
        $container = $this->container([
            'psr' => [
                C::Type->value => HandlerType::Psr,
                C::Options->value => [
                    'logger' => 'inner.logger',
                    'level' => Level::Debug,
                    'include_extra' => true,
                ],
            ],
        ], ['psr'], ['inner.logger' => $collectingLogger]);

        $handler = $container->get(HandlerRegistry::class)->get('psr');
        $this->assertInstanceOf(PsrHandler::class, $handler);

        $logger = $container->get(LoggerInterface::class);
        $this->assertInstanceOf(Logger::class, $logger);
        $logger->warning('Delegated message', ['foo' => 'bar']);

        $this->assertCount(1, $collectingLogger->records);
        $this->assertSame(LogLevel::WARNING, $collectingLogger->records[0]['level']);
        $this->assertSame('Delegated message', $collectingLogger->records[0]['message']);
        $this->assertSame('bar', $collectingLogger->records[0]['context']['foo']);
    }

    public function testHandlerProcessorsKeepConfiguredOrder(): void
    {
        $container = $this->container([
            'test' => [
                C::Type->value => HandlerType::Test,
                C::Processors->value => ['append_a', 'append_b'],
            ],
        ], ['test'], processors: [
            'append_a' => [
                C::Type->value => 'append_message',
                C::Options->value => [
                    'suffix' => 'A',
                ],
            ],
            'append_b' => [
                C::Type->value => 'append_message',
                C::Options->value => [
                    'suffix' => 'B',
                ],
            ],
        ], processorFactories: [
            'append_message' => AppendMessageProcessorFactory::class,
        ]);

        $logger = $container->get(LoggerInterface::class);
        $this->assertInstanceOf(Logger::class, $logger);
        $logger->info('Hello');

        $handler = $container->get(HandlerRegistry::class)->get('test');
        $this->assertInstanceOf(TestHandler::class, $handler);
        $records = $handler->getRecords();
        $this->assertCount(1, $records);
        $this->assertSame('HelloAB', $records[0]->message);
    }

    public function testFormatterOnUnsupportedHandlerFailsFast(): void
    {
        $container = $this->container([
            'noop' => [
                C::Type->value => HandlerType::Noop,
                C::Formatter->value => 'line',
            ],
        ], ['noop'], formatters: [
            'line' => [
                C::Type->value => FormatterType::Line,
            ],
        ]);

        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage("Handler 'noop' has a formatter configured but does not support formatters.");

        $container->get(LoggerInterface::class);
    }

    public function testStreamHandlerUsesMonologDefaultFilePermission(): void
    {
        $container = $this->container([
            'stream' => [
                C::Type->value => HandlerType::Stream,
                C::Options->value => [
                    'stream' => 'php://temp',
                ],
            ],
        ], ['stream']);

        $handler = $container->get(HandlerRegistry::class)->get('stream');
        $this->assertInstanceOf(StreamHandler::class, $handler);

        $reflectionProperty = (new ReflectionClass(StreamHandler::class))->getProperty('filePermission');
        $this->assertNull($reflectionProperty->getValue($handler));
    }

    public function testRotatingFileHandlerCanBeCreated(): void
    {
        $filename = tempnam(sys_get_temp_dir(), 'sirix-monolog-rotating-');
        $this->assertIsString($filename);

        $container = $this->container([
            'rotating' => [
                C::Type->value => HandlerType::RotatingFile,
                C::Options->value => [
                    'filename' => $filename,
                    'max_files' => 1,
                    'file_permission' => null,
                    'use_locking' => true,
                ],
            ],
        ], ['rotating']);

        $handler = $container->get(HandlerRegistry::class)->get('rotating');
        unlink($filename);

        $this->assertInstanceOf(RotatingFileHandler::class, $handler);
    }

    public function testSyslogHandlerCanBeCreated(): void
    {
        $container = $this->container([
            'syslog' => [
                C::Type->value => HandlerType::Syslog,
                C::Options->value => [
                    'ident' => 'sirix-monolog-test',
                    'facility' => LOG_USER,
                    'log_opts' => LOG_PID,
                ],
            ],
        ], ['syslog']);

        $handler = $container->get(HandlerRegistry::class)->get('syslog');

        $this->assertInstanceOf(SyslogHandler::class, $handler);
    }

    public function testErrorLogHandlerCanBeCreated(): void
    {
        $container = $this->container([
            'error_log' => [
                C::Type->value => HandlerType::ErrorLog,
                C::Options->value => [
                    'message_type' => ErrorLogHandler::OPERATING_SYSTEM,
                    'expand_newlines' => true,
                ],
            ],
        ], ['error_log']);

        $handler = $container->get(HandlerRegistry::class)->get('error_log');

        $this->assertInstanceOf(ErrorLogHandler::class, $handler);
    }

    public function testProcessHandlerCanBeCreated(): void
    {
        $container = $this->container([
            'process' => [
                C::Type->value => HandlerType::Process,
                C::Options->value => [
                    'command' => 'cat',
                    'timeout' => 0.1,
                ],
            ],
        ], ['process']);

        $handler = $container->get(HandlerRegistry::class)->get('process');

        $this->assertInstanceOf(ProcessHandler::class, $handler);
    }

    public function testBrowserConsoleHandlersCanBeCreated(): void
    {
        $container = $this->container([
            'browser_console' => [
                C::Type->value => HandlerType::BrowserConsole,
            ],
            'chrome_php' => [
                C::Type->value => HandlerType::ChromePhp,
            ],
            'fire_php' => [
                C::Type->value => HandlerType::FirePhp,
            ],
        ], ['browser_console', 'chrome_php', 'fire_php']);

        $registry = $container->get(HandlerRegistry::class);
        $this->assertInstanceOf(HandlerRegistry::class, $registry);
        $this->assertInstanceOf(BrowserConsoleHandler::class, $registry->get('browser_console'));
        $this->assertInstanceOf(ChromePHPHandler::class, $registry->get('chrome_php'));
        $this->assertInstanceOf(FirePHPHandler::class, $registry->get('fire_php'));
    }

    public function testNativeMailerAndSocketHandlersCanBeCreated(): void
    {
        $container = $this->container([
            'native_mailer' => [
                C::Type->value => HandlerType::NativeMailer,
                C::Options->value => [
                    'to' => ['ops@example.com'],
                    'subject' => 'Critical log: %message%',
                    'from' => 'logs@example.com',
                    'headers' => ['X-App: test'],
                    'parameters' => ['-f logs@example.com'],
                    'content_type' => 'text/plain',
                    'encoding' => 'utf-8',
                ],
            ],
            'socket' => [
                C::Type->value => HandlerType::Socket,
                C::Options->value => [
                    'connection_string' => 'udp://127.0.0.1:514',
                    'timeout' => 0.0,
                    'writing_timeout' => 1.0,
                ],
            ],
        ], ['native_mailer', 'socket']);

        $registry = $container->get(HandlerRegistry::class);
        $this->assertInstanceOf(HandlerRegistry::class, $registry);
        $this->assertInstanceOf(NativeMailerHandler::class, $registry->get('native_mailer'));
        $this->assertInstanceOf(SocketHandler::class, $registry->get('socket'));
    }

    public function testSyslogUdpHandlerCanBeCreated(): void
    {
        if (! extension_loaded('sockets')) {
            $this->markTestSkipped('The sockets extension is required to instantiate SyslogUdpHandler.');
        }

        $container = $this->container([
            'syslog_udp' => [
                C::Type->value => HandlerType::SyslogUdp,
                C::Options->value => [
                    'host' => '127.0.0.1',
                    'port' => 514,
                    'facility' => LOG_USER,
                    'ident' => 'sirix-monolog-test',
                    'rfc' => SyslogUdpHandler::RFC5424,
                ],
            ],
        ], ['syslog_udp']);

        $handler = $container->get(HandlerRegistry::class)->get('syslog_udp');

        $this->assertInstanceOf(SyslogUdpHandler::class, $handler);
    }

    public function testTelegramBotHandlerCanBeCreated(): void
    {
        if (! extension_loaded('curl')) {
            $this->markTestSkipped('The curl extension is required to instantiate TelegramBotHandler.');
        }

        $container = $this->container([
            'telegram_bot' => [
                C::Type->value => HandlerType::TelegramBot,
                C::Options->value => [
                    'api_key' => '123456:test-token',
                    'channel' => '-1001234567890',
                    'parse_mode' => 'HTML',
                    'disable_web_page_preview' => true,
                    'disable_notification' => false,
                    'split_long_messages' => true,
                    'delay_between_messages' => false,
                    'topic' => 1,
                ],
            ],
        ], ['telegram_bot']);

        $handler = $container->get(HandlerRegistry::class)->get('telegram_bot');

        $this->assertInstanceOf(TelegramBotHandler::class, $handler);
    }

    /**
     * @param array<string, array<string, mixed>> $handlers
     * @param list<string>                        $channelHandlers
     * @param array<string, mixed>                $services
     * @param array<string, array<string, mixed>> $processors
     * @param array<string, class-string>         $processorFactories
     * @param array<string, array<string, mixed>> $formatters
     */
    private function container(
        array $handlers,
        array $channelHandlers,
        array $services = [],
        array $processors = [],
        array $processorFactories = [],
        array $formatters = [],
    ): ArrayContainer {
        $providerConfig = (new ConfigProvider())();
        $dependencies = $providerConfig['dependencies'];

        return new ArrayContainer(
            services: [
                'config' => [
                    C::Root->value => [
                        C::Channels->value => [
                            'default' => [
                                C::Name->value => 'app',
                                C::Handlers->value => $channelHandlers,
                            ],
                        ],
                        C::Handlers->value => $handlers,
                        C::Formatters->value => $formatters,
                        C::Processors->value => $processors,
                        C::ProcessorFactories->value => $processorFactories,
                    ],
                ],
                ...$services,
            ],
            factories: $dependencies['factories'],
            aliases: $dependencies['aliases'],
        );
    }
}
