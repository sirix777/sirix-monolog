<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Config;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Sirix\Monolog\Config\MonologConfigReader;
use Sirix\Monolog\Enum\ConfigKey as C;
use Sirix\Monolog\Enum\FormatterType;
use Sirix\Monolog\Enum\HandlerType;
use Sirix\Monolog\Exception\InvalidConfigException;
use Sirix\Monolog\Exception\MissingConfigException;
use Sirix\Monolog\Handler\NoopHandlerFactory;
use Sirix\Test\Monolog\Support\CustomNoopHandlerFactory;

final class MonologConfigReaderTest extends TestCase
{
    public function testReadDefaults(): void
    {
        $config = (new MonologConfigReader())->read([]);

        $this->assertSame('default', $config->channelForLoggerService(LoggerInterface::class));
        $this->assertSame('default', $config->channelForLoggerService('logger.default'));
        $this->assertSame('app', $config->channel('default')->name);
        $this->assertSame(['default'], $config->channel('default')->handlers);
        $this->assertSame(HandlerType::Noop->value, $config->handler('default')->type);
        $this->assertSame(NoopHandlerFactory::class, $config->handlerFactory(HandlerType::Noop->value));
    }

    public function testReadEnumTypesAndReferences(): void
    {
        $config = (new MonologConfigReader())->read([
            C::Root->value => [
                C::LoggerServices->value => [
                    'logger.default' => 'default',
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

        $this->assertSame(HandlerType::Stream->value, $config->handler('main')->type);
        $this->assertSame(FormatterType::Json->value, $config->formatter('json')->type);
        $this->assertSame('json', $config->handler('main')->formatter);
    }

    public function testCustomHandlerFactoryMapOverridesBuiltIns(): void
    {
        $config = (new MonologConfigReader())->read([
            C::Root->value => [
                C::HandlerFactories->value => [
                    HandlerType::Noop->value => CustomNoopHandlerFactory::class,
                ],
            ],
        ]);

        $this->assertSame(CustomNoopHandlerFactory::class, $config->handlerFactory(HandlerType::Noop->value));
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
