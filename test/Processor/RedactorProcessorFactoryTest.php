<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Processor;

use DateTimeImmutable;
use Monolog\Level;
use Monolog\LogRecord;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Processor\RedactorProcessorFactory;
use Sirix\Monolog\Redaction\RedactorProcessor;

class RedactorProcessorFactoryTest extends TestCase
{
    public function testInvokeReturnsProcessorInstance(): void
    {
        $factory = new RedactorProcessorFactory();
        $processor = $factory([]);

        $this->assertInstanceOf(RedactorProcessor::class, $processor);
    }

    public function testInvokeWithOptionsSetsProperties(): void
    {
        $factory = new RedactorProcessorFactory();
        $processor = $factory([
            'rules' => ['email' => []], // shape does not matter here as we do not inspect internal rules
            'useDefaultRules' => false,
            'replacement' => '#',
            'template' => '<%s>',
            'lengthLimit' => 10,
        ]);

        $this->assertInstanceOf(RedactorProcessor::class, $processor);
        $this->assertSame('#', $processor->getReplacement());
        $this->assertSame('<%s>', $processor->getTemplate());
        $this->assertSame(10, $processor->getLengthLimit());

        // also check that null is allowed for lengthLimit and overrides previous value
        $processor2 = $factory([
            'lengthLimit' => null,
        ]);
        $this->assertNull($processor2->getLengthLimit());
    }

    public function testInvokeWithInvalidRulesDoesNotError(): void
    {
        $factory = new RedactorProcessorFactory();
        $processor = $factory([
            // rules must be array, but factory should normalize any non-array to []
            'rules' => 'not-an-array',
        ]);

        $this->assertInstanceOf(RedactorProcessor::class, $processor);
    }

    public function testUseDefaultRulesAppliesEmailRedaction(): void
    {
        $factory = new RedactorProcessorFactory();

        $processorWithDefaults = $factory([]);

        $record = new LogRecord(
            new DateTimeImmutable('2020-01-01T00:00:00Z'),
            'test',
            Level::Info,
            'message',
            ['email' => 'username@example.com']
        );

        $processed = $processorWithDefaults($record);
        $this->assertArrayHasKey('email', $processed->context);
        $this->assertSame('use****@example.com', $processed->context['email']);

        $processorWithoutDefaults = $factory([
            'useDefaultRules' => false,
        ]);

        $processed2 = $processorWithoutDefaults($record);
        $this->assertArrayHasKey('email', $processed2->context);
        $this->assertSame('username@example.com', $processed2->context['email']);
    }
}
