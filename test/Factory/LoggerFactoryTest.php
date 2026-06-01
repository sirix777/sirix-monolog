<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Factory;

use Monolog\Handler\NoopHandler;
use Monolog\Handler\TestHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Sirix\Monolog\ConfigProvider;
use Sirix\Monolog\Enum\ConfigKey as C;
use Sirix\Monolog\Enum\FormatterType;
use Sirix\Monolog\Enum\HandlerType;
use Sirix\Monolog\Enum\ProcessorType;
use Sirix\Monolog\Factory\LoggerFactory;
use Sirix\Monolog\Registry\HandlerRegistry;
use Sirix\Test\Monolog\Support\ArrayContainer;

use function fclose;
use function fopen;
use function rewind;
use function stream_get_contents;

final class LoggerFactoryTest extends TestCase
{
    public function testDefaultLoggerCanBeUsedWithoutConfiguration(): void
    {
        $arrayContainer = ArrayContainer::fromConfigProvider([], (new ConfigProvider())());

        $logger = $arrayContainer->get(LoggerInterface::class);

        $this->assertInstanceOf(Logger::class, $logger);
        $this->assertSame('app', $logger->getName());
        $this->assertInstanceOf(NoopHandler::class, $logger->getHandlers()[0]);

        $logger->info('Message discarded by the default noop handler');
    }

    public function testConfiguredHandlerOrderIsPreserved(): void
    {
        $arrayContainer = ArrayContainer::fromConfigProvider([
            C::Root->value => [
                C::Channels->value => [
                    'default' => [
                        C::Handlers->value => ['first', 'second'],
                    ],
                ],
                C::Handlers->value => [
                    'first' => [
                        C::Type->value => HandlerType::Test,
                    ],
                    'second' => [
                        C::Type->value => HandlerType::Test,
                    ],
                ],
            ],
        ], (new ConfigProvider())());

        $logger = $arrayContainer->get(LoggerInterface::class);
        $registry = $arrayContainer->get(HandlerRegistry::class);

        $this->assertInstanceOf(Logger::class, $logger);
        $this->assertInstanceOf(HandlerRegistry::class, $registry);
        $this->assertSame($registry->get('first'), $logger->getHandlers()[0]);
        $this->assertSame($registry->get('second'), $logger->getHandlers()[1]);
        $this->assertInstanceOf(TestHandler::class, $logger->getHandlers()[0]);
        $this->assertInstanceOf(TestHandler::class, $logger->getHandlers()[1]);
    }

    public function testLoggerWritesThroughConfiguredStreamFormatterAndProcessor(): void
    {
        $stream = fopen('php://temp', 'w+');
        $this->assertIsResource($stream);

        $arrayContainer = ArrayContainer::fromConfigProvider([
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

        $logger = $arrayContainer->get(LoggerInterface::class);
        $logger->info('Hello {name}', ['name' => 'Ada']);

        rewind($stream);
        $contents = stream_get_contents($stream);
        fclose($stream);

        $this->assertIsString($contents);
        $this->assertStringContainsString('Hello Ada', $contents);
        $this->assertStringContainsString('"channel":"app"', $contents);
    }

    public function testLoggerServiceCanOverrideMonologChannelName(): void
    {
        $providerConfig = (new ConfigProvider())();
        $providerConfig['dependencies']['factories']['logger_crypto_transaction'] = LoggerFactory::class;

        $arrayContainer = ArrayContainer::fromConfigProvider([
            C::Root->value => [
                C::LoggerServices->value => [
                    'logger_crypto_transaction' => [
                        C::Channel->value => 'default',
                        C::Name->value => 'CryptoTransactionService',
                    ],
                ],
                C::Channels->value => [
                    'default' => [
                        C::Name->value => 'app',
                        C::Handlers->value => ['default_handler'],
                    ],
                ],
                C::Handlers->value => [
                    'default_handler' => [
                        C::Type->value => HandlerType::Noop,
                    ],
                ],
            ],
        ], $providerConfig);

        $logger = $arrayContainer->get('logger_crypto_transaction');

        $this->assertInstanceOf(Logger::class, $logger);
        $this->assertSame('CryptoTransactionService', $logger->getName());
    }

    public function testRequestedNameSelectsConfiguredChannel(): void
    {
        $providerConfig = (new ConfigProvider())();
        $providerConfig['dependencies']['factories']['logger_audit'] = LoggerFactory::class;

        $arrayContainer = ArrayContainer::fromConfigProvider([
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

        $logger = $arrayContainer->get('logger_audit');

        $this->assertInstanceOf(Logger::class, $logger);
        $this->assertSame('audit', $logger->getName());
    }
}
