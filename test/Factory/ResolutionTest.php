<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Factory;

use ArrayObject;
use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\FingersCrossed\ErrorLevelActivationStrategy;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\TestHandler;
use Monolog\Level;
use Monolog\Logger;
use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Sirix\Monolog\Config\FormatterDefinition;
use Sirix\Monolog\Config\HandlerDefinition;
use Sirix\Monolog\Config\ProcessorDefinition;
use Sirix\Monolog\ConfigProvider;
use Sirix\Monolog\Enum\ConfigKey as C;
use Sirix\Monolog\Enum\FormatterType;
use Sirix\Monolog\Enum\HandlerType;
use Sirix\Monolog\Enum\ProcessorType;
use Sirix\Monolog\Exception\InvalidConfigException;
use Sirix\Monolog\Exception\InvalidFactoryException;
use Sirix\Monolog\Exception\UnknownChannelException;
use Sirix\Monolog\Factory\LoggerFactory;
use Sirix\Monolog\Formatter\FormatterFactoryInterface;
use Sirix\Monolog\Formatter\LineFormatterFactory;
use Sirix\Monolog\Handler\HandlerFactoryInterface;
use Sirix\Monolog\Handler\NullHandlerFactory;
use Sirix\Monolog\Processor\ProcessorFactoryInterface;
use Sirix\Monolog\Processor\UidProcessorFactory;
use Sirix\Monolog\Registry\HandlerRegistry;
use Sirix\Test\Monolog\Support\ArrayContainer;

final class ResolutionTest extends TestCase
{
    public function testChannelOptionSelectsChannel(): void
    {
        $container = $this->container([
            C::Channels->value => [
                'default' => [
                    C::Handlers->value => ['test'],
                ],
                'audit'   => [
                    C::Name->value     => 'audit-log',
                    C::Handlers->value => ['test'],
                ],
            ],
            C::Handlers->value => $this->testHandler(),
        ]);

        $logger = (new LoggerFactory())($container, LoggerInterface::class, [
            'channel' => ' audit ',
        ]);

        $this->assertInstanceOf(Logger::class, $logger);
        $this->assertSame('audit-log', $logger->getName());
    }

    public function testUnknownChannelOptionFails(): void
    {
        $container = $this->container([
            C::Handlers->value => $this->testHandler(),
            C::Channels->value => [
                'default' => [
                    C::Handlers->value => ['test'],
                ],
            ],
        ]);

        $this->expectException(UnknownChannelException::class);
        $this->expectExceptionMessage("Unable to locate monolog channel 'missing'.");

        (new LoggerFactory())($container, LoggerInterface::class, [
            'channel' => 'missing',
        ]);
    }

    public function testUnknownHandlerTypeFailsWhenHandlerIsBuilt(): void
    {
        $container = $this->container([
            C::Handlers->value => [
                'default' => [
                    C::Type->value => 'unknown',
                ],
            ],
        ]);

        $this->expectException(InvalidFactoryException::class);
        $this->expectExceptionMessage("Unable to locate factory for monolog type 'unknown'.");

        $container->get(HandlerRegistry::class)->get('default');
    }

    public function testProcessorsOnHandlerWithoutProcessorSupportFail(): void
    {
        $container = $this->container([
            C::Handlers->value   => [
                'default' => [
                    C::Type->value       => HandlerType::Noop,
                    C::Processors->value => ['uid'],
                ],
            ],
            C::Processors->value => [
                'uid' => [
                    C::Type->value => ProcessorType::Uid,
                ],
            ],
        ]);

        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage("Handler 'default' has processors configured but does not support processors.");

        $container->get(HandlerRegistry::class)->get('default');
    }

    public function testFactoriesRegisteredInContainerAreUsed(): void
    {
        $container = $this->container([
            C::Channels->value   => [
                'default' => [
                    C::Handlers->value   => ['main'],
                    C::Processors->value => ['marker'],
                ],
            ],
            C::Handlers->value   => [
                'main' => [
                    C::Type->value      => HandlerType::Null,
                    C::Formatter->value => 'line',
                ],
            ],
            C::Formatters->value => [
                'line' => [
                    C::Type->value => FormatterType::Line,
                ],
            ],
            C::Processors->value => [
                'marker' => [
                    C::Type->value => ProcessorType::Uid,
                ],
            ],
        ], [
            NullHandlerFactory::class   => new class implements HandlerFactoryInterface {
                public function create(ContainerInterface $container, HandlerDefinition $handlerDefinition): HandlerInterface
                {
                    return new TestHandler();
                }
            },
            LineFormatterFactory::class => new class implements FormatterFactoryInterface {
                public function create(ContainerInterface $container, FormatterDefinition $formatterDefinition): FormatterInterface
                {
                    return new JsonFormatter();
                }
            },
            UidProcessorFactory::class  => new class implements ProcessorFactoryInterface {
                public function create(ContainerInterface $container, ProcessorDefinition $processorDefinition): ProcessorInterface
                {
                    return new class implements ProcessorInterface {
                        public function __invoke(LogRecord $record): LogRecord
                        {
                            return $record->with(extra: [
                                'marker' => 'container',
                            ]);
                        }
                    };
                }
            },
        ]);

        $logger = $container->get(LoggerInterface::class);
        $this->assertInstanceOf(Logger::class, $logger);
        $logger->info('Resolved from container');

        $handler = $container->get(HandlerRegistry::class)->get('main');
        $this->assertInstanceOf(TestHandler::class, $handler);
        $this->assertInstanceOf(JsonFormatter::class, $handler->getFormatter());
        $this->assertSame('container', $handler->getRecords()[0]->extra['marker']);
    }

    public function testFingersCrossedUsesActivationStrategyFromContainer(): void
    {
        $container = $this->container([
            C::Channels->value => [
                'default' => [
                    C::Handlers->value => ['fingers'],
                ],
            ],
            C::Handlers->value => [
                'fingers' => [
                    C::Type->value    => HandlerType::FingersCrossed,
                    C::Options->value => [
                        'handler'             => 'test',
                        'activation_strategy' => 'error.strategy',
                    ],
                ],
                ...$this->testHandler(),
            ],
        ], [
            'error.strategy' => new ErrorLevelActivationStrategy(Level::Error),
        ]);

        $logger = $container->get(LoggerInterface::class);
        $this->assertInstanceOf(Logger::class, $logger);
        $handler = $container->get(HandlerRegistry::class)->get('test');
        $this->assertInstanceOf(TestHandler::class, $handler);

        $logger->warning('Buffered warning');
        $this->assertSame([], $handler->getRecords());

        $logger->error('Activating error');
        $this->assertTrue($handler->hasWarningThatContains('Buffered warning'));
        $this->assertTrue($handler->hasErrorThatContains('Activating error'));
    }

    public function testFingersCrossedPassesThroughRecordsAtPassthruLevelOnClose(): void
    {
        $container = $this->container([
            C::Channels->value => [
                'default' => [
                    C::Handlers->value => ['fingers'],
                ],
            ],
            C::Handlers->value => [
                'fingers' => [
                    C::Type->value    => HandlerType::FingersCrossed,
                    C::Options->value => [
                        'handler'             => 'test',
                        'activation_strategy' => Level::Error,
                        'passthru_level'      => Level::Warning,
                    ],
                ],
                ...$this->testHandler(),
            ],
        ]);

        $logger = $container->get(LoggerInterface::class);
        $this->assertInstanceOf(Logger::class, $logger);
        $logger->info('Dropped info');
        $logger->warning('Passed warning');

        $handler = $container->get(HandlerRegistry::class)->get('test');
        $this->assertInstanceOf(TestHandler::class, $handler);
        $this->assertSame([], $handler->getRecords());

        $logger->close();

        $this->assertTrue($handler->hasWarningThatContains('Passed warning'));
        $this->assertFalse($handler->hasInfoRecords());
    }

    public function testFilterAcceptsExplicitLevelList(): void
    {
        $container = $this->container([
            C::Channels->value => [
                'default' => [
                    C::Handlers->value => ['filter'],
                ],
            ],
            C::Handlers->value => [
                'filter' => [
                    C::Type->value    => HandlerType::Filter,
                    C::Options->value => [
                        'handler'           => 'test',
                        'min_level_or_list' => [Level::Info, 'error'],
                    ],
                ],
                ...$this->testHandler(),
            ],
        ]);

        $logger = $container->get(LoggerInterface::class);
        $this->assertInstanceOf(Logger::class, $logger);
        $logger->info('Accepted info');
        $logger->warning('Filtered warning');
        $logger->error('Accepted error');

        $handler = $container->get(HandlerRegistry::class)->get('test');
        $this->assertInstanceOf(TestHandler::class, $handler);
        $this->assertTrue($handler->hasInfoThatContains('Accepted info'));
        $this->assertFalse($handler->hasWarningRecords());
        $this->assertTrue($handler->hasErrorThatContains('Accepted error'));
    }

    public function testWebProcessorReadsServerDataFromContainer(): void
    {
        $container = $this->container([
            C::Channels->value   => [
                'default' => [
                    C::Handlers->value   => ['test'],
                    C::Processors->value => ['web'],
                ],
            ],
            C::Handlers->value   => $this->testHandler(),
            C::Processors->value => [
                'web' => [
                    C::Type->value    => ProcessorType::Web,
                    C::Options->value => [
                        'server_data'  => 'request.server',
                        'extra_fields' => ['url'],
                    ],
                ],
            ],
        ], [
            'request.server' => new ArrayObject([
                'REQUEST_URI' => '/health',
            ]),
        ]);

        $logger = $container->get(LoggerInterface::class);
        $this->assertInstanceOf(Logger::class, $logger);
        $logger->info('Web request');

        $handler = $container->get(HandlerRegistry::class)->get('test');
        $this->assertInstanceOf(TestHandler::class, $handler);
        $this->assertSame([
            'url' => '/health',
        ], $handler->getRecords()[0]->extra);
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function testHandler(): array
    {
        return [
            'test' => [
                C::Type->value => HandlerType::Test,
            ],
        ];
    }

    /**
     * @param array<string, mixed> $root
     * @param array<string, mixed> $services
     */
    private function container(array $root, array $services = []): ArrayContainer
    {
        return ArrayContainer::fromConfigProvider([
            C::Root->value => $root,
        ], (new ConfigProvider())(), $services);
    }
}
