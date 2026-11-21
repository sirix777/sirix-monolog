<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Factory;

use PHPUnit\Framework\TestCase;
use Sirix\ContainerResolver\Exception\InvalidConfigValueException;
use Sirix\ContainerResolver\Exception\MissingConfigValueException;
use Sirix\Monolog\Config\MonologConfigReader;
use Sirix\Monolog\ConfigProvider;
use Sirix\Monolog\Enum\ConfigKey as C;
use Sirix\Monolog\Enum\FormatterType;
use Sirix\Monolog\Enum\HandlerType;
use Sirix\Monolog\Enum\ProcessorType;
use Sirix\Monolog\Exception\InvalidConfigException;
use Sirix\Monolog\Exception\MissingConfigException;
use Sirix\Monolog\Registry\FormatterRegistry;
use Sirix\Monolog\Registry\HandlerRegistry;
use Sirix\Monolog\Registry\ProcessorRegistry;
use Sirix\Test\Monolog\Support\ArrayContainer;
use Sirix\Test\Monolog\Support\InvalidOptionHandlerFactory;
use Throwable;

final class ConfigErrorTranslationTest extends TestCase
{
    public function testInvalidHandlerOptionBecomesPackageException(): void
    {
        $container = $this->container([
            C::Handlers->value => [
                'main' => [
                    C::Type->value    => HandlerType::Socket,
                    C::Options->value => [
                        'connection_string' => 'udp://127.0.0.1:514',
                        'level'             => 'loud',
                    ],
                ],
            ],
        ]);

        $exception = $this->catch(static fn () => $container->get(HandlerRegistry::class)->get('main'));

        $this->assertInstanceOf(InvalidConfigException::class, $exception);
        $this->assertStringStartsWith("Handler 'main' (socket): ", $exception->getMessage());
        $this->assertStringContainsString('"level"', $exception->getMessage());
        $this->assertInstanceOf(InvalidConfigValueException::class, $exception->getPrevious());
    }

    public function testMissingRequiredHandlerOptionBecomesPackageException(): void
    {
        $container = $this->container([
            C::Handlers->value => [
                'main' => [
                    C::Type->value => HandlerType::Socket,
                ],
            ],
        ]);

        $exception = $this->catch(static fn () => $container->get(HandlerRegistry::class)->get('main'));

        $this->assertInstanceOf(MissingConfigException::class, $exception);
        $this->assertStringStartsWith("Handler 'main' (socket): ", $exception->getMessage());
        $this->assertStringContainsString('"connection_string"', $exception->getMessage());
        $this->assertInstanceOf(MissingConfigValueException::class, $exception->getPrevious());
    }

    public function testNestedHandlerErrorKeepsInnermostContext(): void
    {
        $container = $this->container([
            C::Handlers->value => [
                'main'   => [
                    C::Type->value    => HandlerType::FingersCrossed,
                    C::Options->value => [
                        'handler' => 'stream',
                    ],
                ],
                'stream' => [
                    C::Type->value => HandlerType::Stream,
                ],
            ],
        ]);

        $exception = $this->catch(static fn () => $container->get(HandlerRegistry::class)->get('main'));

        $this->assertInstanceOf(MissingConfigException::class, $exception);
        $this->assertStringStartsWith("Handler 'stream' (stream): ", $exception->getMessage());
        $this->assertInstanceOf(MissingConfigValueException::class, $exception->getPrevious());
    }

    public function testCustomHandlerFactoryErrorBecomesPackageException(): void
    {
        $container = $this->container([
            C::HandlerFactories->value => [
                'custom' => InvalidOptionHandlerFactory::class,
            ],
            C::Handlers->value         => [
                'main' => [
                    C::Type->value    => 'custom',
                    C::Options->value => [
                        'size' => 'large',
                    ],
                ],
            ],
        ]);

        $exception = $this->catch(static fn () => $container->get(HandlerRegistry::class)->get('main'));

        $this->assertInstanceOf(InvalidConfigException::class, $exception);
        $this->assertStringStartsWith("Handler 'main' (custom): ", $exception->getMessage());
        $this->assertInstanceOf(InvalidConfigValueException::class, $exception->getPrevious());
    }

    public function testHandlerFormatterErrorNamesFormatter(): void
    {
        $container = $this->container([
            C::Handlers->value   => [
                'main' => [
                    C::Type->value      => HandlerType::Test,
                    C::Formatter->value => 'json',
                ],
            ],
            C::Formatters->value => [
                'json' => [
                    C::Type->value    => FormatterType::Json,
                    C::Options->value => [
                        'append_newline' => 'yes',
                    ],
                ],
            ],
        ]);

        $exception = $this->catch(static fn () => $container->get(HandlerRegistry::class)->get('main'));

        $this->assertInstanceOf(InvalidConfigException::class, $exception);
        $this->assertStringStartsWith("Formatter 'json' (json): ", $exception->getMessage());
    }

    public function testInvalidFormatterOptionBecomesPackageException(): void
    {
        $container = $this->container([
            C::Formatters->value => [
                'main' => [
                    C::Type->value    => FormatterType::Json,
                    C::Options->value => [
                        'append_newline' => 'yes',
                    ],
                ],
            ],
        ]);

        $exception = $this->catch(static fn () => $container->get(FormatterRegistry::class)->get('main'));

        $this->assertInstanceOf(InvalidConfigException::class, $exception);
        $this->assertStringStartsWith("Formatter 'main' (json): ", $exception->getMessage());
        $this->assertInstanceOf(InvalidConfigValueException::class, $exception->getPrevious());
    }

    public function testInvalidProcessorOptionBecomesPackageException(): void
    {
        $container = $this->container([
            C::Processors->value => [
                'main' => [
                    C::Type->value    => ProcessorType::Redactor,
                    C::Options->value => [
                        'object_view_mode' => 'unknown',
                    ],
                ],
            ],
        ]);

        $exception = $this->catch(static fn () => $container->get(ProcessorRegistry::class)->get('main'));

        $this->assertInstanceOf(InvalidConfigException::class, $exception);
        $this->assertStringStartsWith("Processor 'main' (redactor): ", $exception->getMessage());
        $this->assertStringContainsString('"object_view_mode"', $exception->getMessage());
        $this->assertInstanceOf(InvalidConfigValueException::class, $exception->getPrevious());
    }

    public function testInvalidConfigValueBecomesPackageException(): void
    {
        $exception = $this->catch(static fn () => (new MonologConfigReader())->read([
            C::Root->value => [
                C::Handlers->value => [
                    'default' => [
                        C::Type->value      => HandlerType::Noop,
                        C::Formatter->value => 123,
                    ],
                ],
            ],
        ]));

        $this->assertInstanceOf(InvalidConfigException::class, $exception);
        $this->assertStringStartsWith('Monolog config: ', $exception->getMessage());
        $this->assertInstanceOf(InvalidConfigValueException::class, $exception->getPrevious());
    }

    public function testMissingConfigValueBecomesPackageException(): void
    {
        $exception = $this->catch(static fn () => (new MonologConfigReader())->read([
            C::Root->value => [
                C::Handlers->value => [
                    'default' => [],
                ],
            ],
        ]));

        $this->assertInstanceOf(MissingConfigException::class, $exception);
        $this->assertStringStartsWith('Monolog config: ', $exception->getMessage());
        $this->assertInstanceOf(MissingConfigValueException::class, $exception->getPrevious());
    }

    private function catch(callable $callable): Throwable
    {
        try {
            $callable();
        } catch (Throwable $throwable) {
            return $throwable;
        }

        $this->fail('Expected an exception.');
    }

    /**
     * @param array<string, mixed> $root
     */
    private function container(array $root): ArrayContainer
    {
        return ArrayContainer::fromConfigProvider([
            C::Root->value => [
                ...$root,
                C::Handlers->value => [
                    'default' => [
                        C::Type->value => HandlerType::Noop,
                    ],
                    ...$root[C::Handlers->value] ?? [],
                ],
            ],
        ], (new ConfigProvider())());
    }
}
