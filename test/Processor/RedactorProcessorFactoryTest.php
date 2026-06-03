<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Processor;

use DateTimeImmutable;
use Monolog\Level;
use Monolog\LogRecord;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Sirix\Monolog\Config\ProcessorDefinition;
use Sirix\Monolog\Exception\InvalidConfigException;
use Sirix\Monolog\Processor\RedactorProcessorFactory;
use Sirix\Redaction\Bridge\Monolog\RedactorProcessor;
use Sirix\Redaction\RedactorInterface;
use Sirix\Redaction\Rule\FullMaskRule;
use Sirix\Test\Monolog\Support\ArrayContainer;

final class RedactorProcessorFactoryTest extends TestCase
{
    private RedactorProcessorFactory $redactorProcessorFactory;
    private ContainerInterface|MockObject $container;

    protected function setUp(): void
    {
        $this->redactorProcessorFactory = new RedactorProcessorFactory();
        $this->container = $this->createMock(ContainerInterface::class);
        $this->container->method('has')->willReturn(false);
    }

    public function testCreateReturnsProcessorInstance(): void
    {
        $redactorProcessor = $this->createProcessor([]);

        $this->assertInstanceOf(RedactorProcessor::class, $redactorProcessor);
    }

    public function testUsesRedactorFromContainerWhenAvailable(): void
    {
        $mockRedactor = $this->createMock(RedactorInterface::class);
        $mockRedactor->method('redact')->willReturn(['x' => 'y']);

        $arrayContainer = new ArrayContainer([
            RedactorInterface::class => $mockRedactor,
        ]);

        $redactorProcessor = $this->createProcessor([], $arrayContainer);
        $logRecord = $redactorProcessor($this->record(['a' => 'b']));

        $this->assertSame(['x' => 'y'], $logRecord->context);
    }

    public function testAppliesCustomRuleFullMaskWithDefaultReplacement(): void
    {
        $redactorProcessor = $this->createProcessor([
            'rules' => [
                'password' => new FullMaskRule(),
            ],
        ]);

        $logRecord = $redactorProcessor($this->record(['password' => 'secret']));

        $this->assertSame('******', $logRecord->context['password']);
    }

    public function testCustomReplacementAffectsMasking(): void
    {
        $redactorProcessor = $this->createProcessor([
            'replacement' => '#',
            'rules' => [
                'password' => new FullMaskRule(),
            ],
        ]);

        $logRecord = $redactorProcessor($this->record(['password' => 'secret']));

        $this->assertSame('######', $logRecord->context['password']);
    }

    public function testAppliesRuleToAllMatchingKeys(): void
    {
        $redactorProcessor = $this->createProcessor([
            'rules' => [
                'password' => new FullMaskRule(),
            ],
            'use_default_rules' => false,
        ]);

        $logRecord = $redactorProcessor($this->record([
            'user' => [
                'name' => 'John',
                'password' => 'topsecret',
            ],
            'account' => [
                'full_name' => 'John Doe',
                'password' => 'topsecret',
            ],
        ]));

        $this->assertSame('*********', $logRecord->context['user']['password']);
        $this->assertSame('*********', $logRecord->context['account']['password']);
        $this->assertSame('John', $logRecord->context['user']['name']);
    }

    public function testDisablesDefaultRulesWhenConfigured(): void
    {
        $record = $this->record(['email' => 'john.doe@example.com']);

        $redactorProcessor = $this->createProcessor([
            'use_default_rules' => false,
        ]);
        $processed = $redactorProcessor($record);

        $this->assertSame('john.doe@example.com', $processed->context['email']);

        $processorWithDefaults = $this->createProcessor([]);
        $logRecord = $processorWithDefaults($record);

        $this->assertNotSame('john.doe@example.com', $logRecord->context['email']);
    }

    public function testInvalidOverflowPlaceholderFailsWithConfigException(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('Redactor option "overflow_placeholder" must be a string or null.');

        $this->createProcessor([
            'overflow_placeholder' => 123,
        ]);
    }

    /**
     * @param array<string, mixed> $options
     */
    private function createProcessor(array $options, ?ContainerInterface $container = null): RedactorProcessor
    {
        return $this->redactorProcessorFactory->create(
            $container ?? $this->container,
            new ProcessorDefinition('redactor', 'redactor', $options),
        );
    }

    /**
     * @param array<string, mixed> $context
     */
    private function record(array $context): LogRecord
    {
        return new LogRecord(
            datetime: new DateTimeImmutable('2025-10-10T00:00:00Z'),
            channel: 'test',
            level: Level::Info,
            message: 'hello',
            context: $context,
            extra: [],
        );
    }
}
