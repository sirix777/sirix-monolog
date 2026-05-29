<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Factory;

use const LOG_PID;
use const LOG_USER;

use Monolog\Handler\ErrorLogHandler;
use Monolog\Handler\ProcessHandler;
use Monolog\Handler\PsrHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\SyslogHandler;
use Monolog\Handler\TestHandler;
use Monolog\Level;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Sirix\Monolog\ConfigProvider;
use Sirix\Monolog\Enum\ConfigKey as C;
use Sirix\Monolog\Enum\HandlerType;
use Sirix\Monolog\Registry\HandlerRegistry;
use Sirix\Test\Monolog\Support\ArrayContainer;
use Sirix\Test\Monolog\Support\CollectingLogger;

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
        $innerLogger = new CollectingLogger();
        $container = $this->container([
            'psr' => [
                C::Type->value => HandlerType::Psr,
                C::Options->value => [
                    'logger' => 'inner.logger',
                    'level' => Level::Debug,
                    'include_extra' => true,
                ],
            ],
        ], ['psr'], ['inner.logger' => $innerLogger]);

        $handler = $container->get(HandlerRegistry::class)->get('psr');
        $this->assertInstanceOf(PsrHandler::class, $handler);

        $logger = $container->get(LoggerInterface::class);
        $this->assertInstanceOf(Logger::class, $logger);
        $logger->warning('Delegated message', ['foo' => 'bar']);

        $this->assertCount(1, $innerLogger->records);
        $this->assertSame(LogLevel::WARNING, $innerLogger->records[0]['level']);
        $this->assertSame('Delegated message', $innerLogger->records[0]['message']);
        $this->assertSame('bar', $innerLogger->records[0]['context']['foo']);
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

    /**
     * @param array<string, array<string, mixed>> $handlers
     * @param list<string>                        $channelHandlers
     * @param array<string, mixed>                $services
     */
    private function container(array $handlers, array $channelHandlers, array $services = []): ArrayContainer
    {
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
                    ],
                ],
                ...$services,
            ],
            factories: $dependencies['factories'],
            aliases: $dependencies['aliases'],
        );
    }
}
