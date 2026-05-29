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
use Sirix\Monolog\Processor\RedactorProcessorFactory;
use Sirix\Redaction\Bridge\Monolog\RedactorProcessor;
use Sirix\Redaction\RedactorInterface;
use Sirix\Redaction\Rule\FullMaskRule;
use Sirix\Test\Monolog\Support\ArrayContainer;

final class RedactorProcessorFactoryTest extends TestCase
{
    private RedactorProcessorFactory $factory;
    private ContainerInterface|MockObject $container;

    protected function setUp(): void
    {
        $this->factory = new RedactorProcessorFactory();
        $this->container = $this->createMock(ContainerInterface::class);
        $this->container->method('has')->willReturn(false);
    }

    public function testCreateReturnsProcessorInstance(): void
    {
        $processor = $this->createProcessor([]);

        $this->assertInstanceOf(RedactorProcessor::class, $processor);
    }

    public function testUsesRedactorFromContainerWhenAvailable(): void
    {
        $mockRedactor = $this->createMock(RedactorInterface::class);
        $mockRedactor->method('redact')->willReturn(['x' => 'y']);

        $container = new ArrayContainer([
            RedactorInterface::class => $mockRedactor,
        ]);

        $processor = $this->createProcessor([], $container);
        $processed = $processor($this->record(['a' => 'b']));

        $this->assertSame(['x' => 'y'], $processed->context);
    }

    public function testAppliesCustomRuleFullMaskWithDefaultReplacement(): void
    {
        $processor = $this->createProcessor([
            'rules' => [
                'password' => new FullMaskRule(),
            ],
        ]);

        $processed = $processor($this->record(['password' => 'secret']));

        $this->assertSame('******', $processed->context['password']);
    }

    public function testCustomReplacementAffectsMasking(): void
    {
        $processor = $this->createProcessor([
            'replacement' => '#',
            'rules' => [
                'password' => new FullMaskRule(),
            ],
        ]);

        $processed = $processor($this->record(['password' => 'secret']));

        $this->assertSame('######', $processed->context['password']);
    }

    public function testAppliesRuleToAllMatchingKeys(): void
    {
        $processor = $this->createProcessor([
            'rules' => [
                'password' => new FullMaskRule(),
            ],
            'use_default_rules' => false,
        ]);

        $processed = $processor($this->record([
            'user' => [
                'name' => 'John',
                'password' => 'topsecret',
            ],
            'account' => [
                'full_name' => 'John Doe',
                'password' => 'topsecret',
            ],
        ]));

        $this->assertSame('*********', $processed->context['user']['password']);
        $this->assertSame('*********', $processed->context['account']['password']);
        $this->assertSame('John', $processed->context['user']['name']);
    }

    public function testDisablesDefaultRulesWhenConfigured(): void
    {
        $record = $this->record(['email' => 'john.doe@example.com']);

        $processor = $this->createProcessor([
            'use_default_rules' => false,
        ]);
        $processed = $processor($record);

        $this->assertSame('john.doe@example.com', $processed->context['email']);

        $processorWithDefaults = $this->createProcessor([]);
        $processedWithDefaults = $processorWithDefaults($record);

        $this->assertNotSame('john.doe@example.com', $processedWithDefaults->context['email']);
    }

    /**
     * @param array<string, mixed> $options
     */
    private function createProcessor(array $options, ?ContainerInterface $container = null): RedactorProcessor
    {
        return $this->factory->create(
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
