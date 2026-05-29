<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Factory;

use Monolog\Handler\TestHandler;
use Monolog\Level;
use Monolog\Logger;
use Monolog\LogRecord;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Sirix\Monolog\ConfigProvider;
use Sirix\Monolog\Enum\ConfigKey as C;
use Sirix\Monolog\Enum\HandlerType;
use Sirix\Monolog\Enum\ProcessorType;
use Sirix\Monolog\Registry\HandlerRegistry;
use Sirix\Redaction\Rule\FullMaskRule;
use Sirix\Test\Monolog\Support\ArrayContainer;
use Sirix\Test\Monolog\Support\CustomExtraProcessorFactory;

use function strlen;

final class ProcessorFactoryTest extends TestCase
{
    public function testCoreProcessorsEnrichRecords(): void
    {
        $container = $this->container([
            'psr_message' => [
                C::Type->value => ProcessorType::PsrLogMessage,
            ],
            'memory_usage' => [
                C::Type->value => ProcessorType::MemoryUsage,
                C::Options->value => [
                    'use_formatting' => false,
                ],
            ],
            'memory_peak' => [
                C::Type->value => ProcessorType::MemoryPeakUsage,
                C::Options->value => [
                    'use_formatting' => false,
                ],
            ],
            'process_id' => [
                C::Type->value => ProcessorType::ProcessId,
            ],
            'uid' => [
                C::Type->value => ProcessorType::Uid,
                C::Options->value => [
                    'length' => 12,
                ],
            ],
            'hostname' => [
                C::Type->value => ProcessorType::Hostname,
            ],
            'tags' => [
                C::Type->value => ProcessorType::Tags,
                C::Options->value => [
                    'tags' => ['api', 'test'],
                ],
            ],
            'web' => [
                C::Type->value => ProcessorType::Web,
                C::Options->value => [
                    'server_data' => [
                        'REQUEST_URI' => '/hello',
                        'REMOTE_ADDR' => '127.0.0.1',
                        'REQUEST_METHOD' => 'GET',
                        'SERVER_NAME' => 'localhost',
                        'HTTP_REFERER' => 'https://example.com',
                    ],
                    'extra_fields' => ['url', 'ip', 'http_method'],
                ],
            ],
            'introspection' => [
                C::Type->value => ProcessorType::Introspection,
                C::Options->value => [
                    'level' => Level::Debug,
                    'skip_classes_partials' => [],
                    'skip_stack_frames_count' => 0,
                ],
            ],
        ], [
            'psr_message',
            'memory_usage',
            'memory_peak',
            'process_id',
            'uid',
            'hostname',
            'tags',
            'web',
            'introspection',
        ]);

        $logger = $container->get(LoggerInterface::class);
        $this->assertInstanceOf(Logger::class, $logger);
        $logger->info('Hello {name}', ['name' => 'Ada']);

        $record = $this->firstRecord($container);

        $this->assertSame('Hello Ada', $record->message);
        $this->assertArrayHasKey('memory_usage', $record->extra);
        $this->assertArrayHasKey('memory_peak_usage', $record->extra);
        $this->assertArrayHasKey('process_id', $record->extra);
        $this->assertArrayHasKey('hostname', $record->extra);
        $this->assertArrayHasKey('file', $record->extra);
        $this->assertSame(12, strlen((string) $record->extra['uid']));
        $this->assertSame(['api', 'test'], $record->extra['tags']);
        $this->assertSame('/hello', $record->extra['url']);
        $this->assertSame('127.0.0.1', $record->extra['ip']);
        $this->assertSame('GET', $record->extra['http_method']);
    }

    public function testRedactorProcessorMasksConfiguredContext(): void
    {
        $container = $this->container([
            'redactor' => [
                C::Type->value => ProcessorType::Redactor,
                C::Options->value => [
                    'use_default_rules' => false,
                    'rules' => [
                        'password' => new FullMaskRule(),
                    ],
                ],
            ],
        ], ['redactor']);

        $logger = $container->get(LoggerInterface::class);
        $this->assertInstanceOf(Logger::class, $logger);
        $logger->info('Sensitive data', ['password' => 'secret']);

        $record = $this->firstRecord($container);

        $this->assertSame('******', $record->context['password']);
    }

    public function testCustomProcessorFactoryCanBeRegisteredAndUsed(): void
    {
        $container = $this->container([
            'tenant' => [
                C::Type->value => 'tenant',
                C::Options->value => [
                    'field' => 'tenant_id',
                    'value' => 'acme',
                ],
            ],
        ], ['tenant'], [
            'tenant' => CustomExtraProcessorFactory::class,
        ]);

        $logger = $container->get(LoggerInterface::class);
        $this->assertInstanceOf(Logger::class, $logger);
        $logger->info('Custom processor message');

        $record = $this->firstRecord($container);

        $this->assertSame('acme', $record->extra['tenant_id']);
    }

    /**
     * @param array<string, array<string, mixed>> $processors
     * @param list<string>                        $channelProcessors
     * @param array<string, class-string>         $processorFactories
     */
    private function container(
        array $processors,
        array $channelProcessors,
        array $processorFactories = []
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
                                C::Handlers->value => ['test'],
                                C::Processors->value => $channelProcessors,
                            ],
                        ],
                        C::Handlers->value => [
                            'test' => [
                                C::Type->value => HandlerType::Test,
                            ],
                        ],
                        C::Processors->value => $processors,
                        C::ProcessorFactories->value => $processorFactories,
                    ],
                ],
            ],
            factories: $dependencies['factories'],
            aliases: $dependencies['aliases'],
        );
    }

    private function firstRecord(ArrayContainer $container): LogRecord
    {
        $handler = $container->get(HandlerRegistry::class)->get('test');
        $this->assertInstanceOf(TestHandler::class, $handler);
        $records = $handler->getRecords();
        $this->assertCount(1, $records);

        return $records[0];
    }
}
