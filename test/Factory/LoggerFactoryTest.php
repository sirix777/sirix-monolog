<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Factory;

use Monolog\Handler\NoopHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Sirix\Monolog\ConfigProvider;
use Sirix\Monolog\Enum\ConfigKey as C;
use Sirix\Monolog\Enum\FormatterType;
use Sirix\Monolog\Enum\HandlerType;
use Sirix\Monolog\Enum\ProcessorType;
use Sirix\Monolog\Factory\LoggerFactory;
use Sirix\Test\Monolog\Support\ArrayContainer;

use function fclose;
use function fopen;
use function rewind;
use function stream_get_contents;

final class LoggerFactoryTest extends TestCase
{
    public function testDefaultLoggerCanBeUsedWithoutConfiguration(): void
    {
        $container = ArrayContainer::fromConfigProvider([], (new ConfigProvider())());

        $logger = $container->get(LoggerInterface::class);

        $this->assertInstanceOf(Logger::class, $logger);
        $this->assertSame('app', $logger->getName());
        $this->assertInstanceOf(NoopHandler::class, $logger->getHandlers()[0]);

        $logger->info('Message discarded by the default noop handler');
    }

    public function testLoggerWritesThroughConfiguredStreamFormatterAndProcessor(): void
    {
        $stream = fopen('php://temp', 'w+');
        $this->assertIsResource($stream);

        $container = ArrayContainer::fromConfigProvider([
            C::Root->value => [
                C::LoggerServices->value => [
                    'logger' => 'default',
                ],
                C::Channels->value => [
                    'default' => [
                        C::Name->value => 'app',
                        C::Handlers->value => ['main'],
                    ],
                ],
                C::Handlers->value => [
                    'main' => [
                        C::Type->value => HandlerType::Stream,
                        C::Options->value => [
                            'stream' => $stream,
                        ],
                        C::Formatter->value => 'json',
                        C::Processors->value => ['psr_message'],
                    ],
                ],
                C::Formatters->value => [
                    'json' => [
                        C::Type->value => FormatterType::Json,
                    ],
                ],
                C::Processors->value => [
                    'psr_message' => [
                        C::Type->value => ProcessorType::PsrLogMessage,
                    ],
                ],
            ],
        ], (new ConfigProvider())());

        $logger = $container->get(LoggerInterface::class);
        $logger->info('Hello {name}', ['name' => 'Ada']);

        rewind($stream);
        $contents = stream_get_contents($stream);
        fclose($stream);

        $this->assertIsString($contents);
        $this->assertStringContainsString('Hello Ada', $contents);
        $this->assertStringContainsString('"channel":"app"', $contents);
    }

    public function testRequestedNameSelectsConfiguredChannel(): void
    {
        $providerConfig = (new ConfigProvider())();
        $providerConfig['dependencies']['factories']['logger_audit'] = LoggerFactory::class;

        $container = ArrayContainer::fromConfigProvider([
            C::Root->value => [
                C::LoggerServices->value => [
                    'logger' => 'default',
                    'logger_audit' => 'audit',
                ],
                C::Channels->value => [
                    'default' => [
                        C::Handlers->value => ['default_handler'],
                    ],
                    'audit' => [
                        C::Name->value => 'audit',
                        C::Handlers->value => ['audit_handler'],
                    ],
                ],
                C::Handlers->value => [
                    'default_handler' => [
                        C::Type->value => HandlerType::Noop,
                    ],
                    'audit_handler' => [
                        C::Type->value => HandlerType::Noop,
                    ],
                ],
            ],
        ], $providerConfig);

        $logger = $container->get('logger_audit');

        $this->assertInstanceOf(Logger::class, $logger);
        $this->assertSame('audit', $logger->getName());
    }
}
