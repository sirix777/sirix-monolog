<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Processor;

use DateTimeImmutable;
use Monolog\Level;
use Monolog\LogRecord;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Sirix\Monolog\Processor\RedactorProcessorFactory;
use Sirix\Redaction\Bridge\Monolog\RedactorProcessor;
use Sirix\Redaction\RedactorInterface;
use Sirix\Redaction\Rule\FullMaskRule;

final class RedactorProcessorFactoryTest extends TestCase
{
    private RedactorProcessorFactory $factory;
    private ContainerInterface|MockObject $mockContainer;

    protected function setUp(): void
    {
        $this->factory = new RedactorProcessorFactory();
        $this->mockContainer = $this->createMock(ContainerInterface::class);
        $this->factory->setContainer($this->mockContainer);
        $this->mockContainer->method('has')->willReturn(false);
    }

    public function testInvokeReturnsProcessorInstance(): void
    {
        $processor = $this->factory->__invoke([]);
        $this->assertInstanceOf(RedactorProcessor::class, $processor);
    }

    public function testUsesRedactorFromContainerWhenAvailable(): void
    {
        $mockRedactor = $this->createMock(RedactorInterface::class);
        $mockRedactor->method('redact')->willReturn(['x' => 'y']);

        $this->mockContainer->method('has')
            ->with(RedactorInterface::class)
            ->willReturn(true)
        ;
        $this->mockContainer->method('get')
            ->with(RedactorInterface::class)
            ->willReturn($mockRedactor)
        ;

        $processor = new RedactorProcessor($mockRedactor);

        $record = new LogRecord(
            datetime: new DateTimeImmutable('2025-10-10T00:00:00Z'),
            channel: 'test',
            level: Level::Info,
            message: 'hello',
            context: ['a' => 'b'],
            extra: []
        );

        $processed = $processor($record);

        $this->assertSame(['x' => 'y'], $processed->context);
    }

    public function testAppliesCustomRuleFullMaskWithDefaultReplacement(): void
    {
        $processor = $this->factory->__invoke([
            'rules' => [
                'password' => new FullMaskRule(),
            ],
        ]);

        $record = new LogRecord(
            datetime: new DateTimeImmutable('2025-10-10T00:00:00Z'),
            channel: 'test',
            level: Level::Info,
            message: 'hello',
            context: ['password' => 'secret'],
            extra: []
        );

        $processed = $processor($record);

        // Full mask of 6 characters using default replacement '*'
        $this->assertSame('******', $processed->context['password']);
    }

    public function testCustomReplacementAffectsMasking(): void
    {
        $processor = $this->factory->__invoke([
            'replacement' => '#',
            'rules' => [
                'password' => new FullMaskRule(),
            ],
        ]);

        $record = new LogRecord(
            datetime: new DateTimeImmutable('2025-10-10T00:00:00Z'),
            channel: 'test',
            level: Level::Info,
            message: 'hello',
            context: ['password' => 'secret'],
            extra: []
        );

        $processed = $processor($record);

        $this->assertSame('######', $processed->context['password']);
    }

    public function testNestedRulesAreApplied(): void
    {
        $processor = $this->factory->__invoke([
            'rules' => [
                'user' => [
                    'password' => new FullMaskRule(),
                ],
            ],
        ]);

        $context = [
            'user' => [
                'name' => 'John',
                'password' => 'topsecret',
            ],
        ];

        $record = new LogRecord(
            datetime: new DateTimeImmutable('2025-10-10T00:00:00Z'),
            channel: 'test',
            level: Level::Info,
            message: 'hello',
            context: $context,
            extra: []
        );

        $processed = $processor($record);

        $this->assertSame('*********', $processed->context['user']['password']);
        $this->assertSame('John', $processed->context['user']['name']);
    }

    public function testDisablesDefaultRulesWhenConfigured(): void
    {
        // With defaults disabled and no custom rule, email should stay as-is
        $processor = $this->factory->__invoke([
            'useDefaultRules' => false,
        ]);

        $record = new LogRecord(
            datetime: new DateTimeImmutable('2025-10-10T00:00:00Z'),
            channel: 'test',
            level: Level::Info,
            message: 'hello',
            context: ['email' => 'john.doe@example.com'],
            extra: []
        );

        $processed = $processor($record);

        $this->assertSame('john.doe@example.com', $processed->context['email']);

        // With defaults enabled (default behavior), email should be masked
        $processorWithDefaults = $this->factory->__invoke([]);
        $processedWithDefaults = $processorWithDefaults($record);

        $this->assertNotSame('john.doe@example.com', $processedWithDefaults->context['email']);
    }
}
