<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Config;

use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Sirix\Monolog\Config\MonologConfigReader;
use Sirix\Monolog\Enum\ConfigKey as C;
use Sirix\Monolog\Enum\FormatterType;
use Sirix\Monolog\Enum\HandlerType;
use Sirix\Monolog\Exception\InvalidConfigException;
use Sirix\Monolog\Exception\MissingConfigException;
use Sirix\Monolog\Exception\UnknownChannelException;
use Sirix\Monolog\Handler\NoopHandlerFactory;
use Sirix\Test\Monolog\Support\CustomNoopHandlerFactory;

final class MonologConfigReaderTest extends TestCase
{
    public function testReadDefaults(): void
    {
        $monologConfig = (new MonologConfigReader())->read([]);

        $this->assertSame('default', $monologConfig->channelForLoggerService(Logger::class));
        $this->assertSame('default', $monologConfig->channelForLoggerService(LoggerInterface::class));
        $this->assertSame('default', $monologConfig->channelForLoggerService('logger'));
        $this->assertSame('app', $monologConfig->channel('default')->name);
        $this->assertSame(['default'], $monologConfig->channel('default')->handlers);
        $this->assertSame(HandlerType::Noop->value, $monologConfig->handler('default')->type);
        $this->assertSame(NoopHandlerFactory::class, $monologConfig->handlerFactory(HandlerType::Noop->value));
    }

    public function testReadEnumTypesAndReferences(): void
    {
        $monologConfig = (new MonologConfigReader())->read([
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
                            'stream' => 'php://stderr',
                        ],
                        C::Formatter->value => 'json',
                    ],
                ],
                C::Formatters->value => [
                    'json' => [
                        C::Type->value => FormatterType::Json,
                    ],
                ],
            ],
        ]);

        $this->assertSame(HandlerType::Stream->value, $monologConfig->handler('main')->type);
        $this->assertSame(FormatterType::Json->value, $monologConfig->formatter('json')->type);
        $this->assertSame('json', $monologConfig->handler('main')->formatter);
    }

    public function testReadLoggerServiceNameOverride(): void
    {
        $monologConfig = (new MonologConfigReader())->read([
            C::Root->value => [
                C::LoggerServices->value => [
                    'logger_crypto_transaction' => [
                        C::Channel->value => 'default',
                        C::Name->value => 'CryptoTransactionService',
                    ],
                ],
            ],
        ]);

        $loggerService = $monologConfig->loggerService('logger_crypto_transaction');

        $this->assertSame('default', $loggerService->channel);
        $this->assertSame('CryptoTransactionService', $loggerService->name);
    }

    public function testExplicitLoggerServicesReplaceDefaults(): void
    {
        $monologConfig = (new MonologConfigReader())->read([
            C::Root->value => [
                C::LoggerServices->value => [
                    'logger_audit' => 'default',
                ],
            ],
        ]);

        $this->assertSame('default', $monologConfig->channelForLoggerService('logger_audit'));

        $this->expectException(UnknownChannelException::class);
        $this->expectExceptionMessage("Unable to resolve monolog channel for logger service 'logger'.");

        $monologConfig->channelForLoggerService('logger');
    }

    public function testMissingLoggerServiceChannelReferenceFailsEarly(): void
    {
        $this->expectException(MissingConfigException::class);
        $this->expectExceptionMessage("Logger service 'logger_missing' references unknown channel 'missing'.");

        (new MonologConfigReader())->read([
            C::Root->value => [
                C::LoggerServices->value => [
                    'logger_missing' => 'missing',
                ],
            ],
        ]);
    }

    public function testCustomHandlerFactoryMapOverridesBuiltIns(): void
    {
        $monologConfig = (new MonologConfigReader())->read([
            C::Root->value => [
                C::HandlerFactories->value => [
                    HandlerType::Noop->value => CustomNoopHandlerFactory::class,
                ],
            ],
        ]);

        $this->assertSame(CustomNoopHandlerFactory::class, $monologConfig->handlerFactory(HandlerType::Noop->value));
    }

    public function testMissingHandlerReferenceFailsEarly(): void
    {
        $this->expectException(MissingConfigException::class);
        $this->expectExceptionMessage("Channel 'default' references unknown handler 'missing'.");

        (new MonologConfigReader())->read([
            C::Root->value => [
                C::Channels->value => [
                    'default' => [
                        C::Handlers->value => ['missing'],
                    ],
                ],
            ],
        ]);
    }

    public function testInvalidTypeFailsStrictly(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage("Config value 'handlers.main.type' must be a non-empty string.");

        (new MonologConfigReader())->read([
            C::Root->value => [
                C::Channels->value => [
                    'default' => [
                        C::Handlers->value => ['main'],
                    ],
                ],
                C::Handlers->value => [
                    'main' => [
                        C::Type->value => 123,
                    ],
                ],
            ],
        ]);
    }
}
