<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Processor;

use DateTimeImmutable;
use Monolog\Level;
use Monolog\LogRecord;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\Exception\ConfigReaderException;
use Sirix\Monolog\Config\ProcessorDefinition;
use Sirix\Monolog\Exception\InvalidConfigException;
use Sirix\Monolog\Processor\RedactorProcessorFactory;
use Sirix\Redaction\Bridge\Monolog\RedactorProcessor;
use Sirix\Redaction\Enum\ObjectViewModeEnum;
use Sirix\Redaction\RedactorInterface;
use Sirix\Redaction\Rule\FullMaskRule;
use Sirix\Redaction\Rule\OffsetRule;
use Sirix\Test\Monolog\Support\ArrayContainer;

final class RedactorProcessorFactoryTest extends TestCase
{
    private RedactorProcessorFactory $redactorProcessorFactory;
    private ContainerInterface&Stub $container;

    protected function setUp(): void
    {
        $this->redactorProcessorFactory = new RedactorProcessorFactory();
        $this->container                = $this->createStub(ContainerInterface::class);
        $this->container->method('has')->willReturn(false);
    }

    public function testCreateReturnsProcessorInstance(): void
    {
        $redactorProcessor = $this->createProcessor([]);

        $this->assertInstanceOf(RedactorProcessor::class, $redactorProcessor);
    }

    public function testUsesRedactorFromContainerWhenAvailable(): void
    {
        $mockRedactor = $this->createStub(RedactorInterface::class);
        $mockRedactor->method('redact')->willReturn([
            'x' => 'y',
        ]);

        $arrayContainer = new ArrayContainer([
            RedactorInterface::class => $mockRedactor,
        ]);

        $redactorProcessor = $this->createProcessor([], $arrayContainer);
        $logRecord         = $redactorProcessor($this->record([
            'a' => 'b',
        ]));

        $this->assertSame([
            'x' => 'y',
        ], $logRecord->context);
    }

    public function testAppliesCustomRuleFullMaskWithDefaultReplacement(): void
    {
        $redactorProcessor = $this->createProcessor([
            'rules' => [
                'password' => new FullMaskRule(),
            ],
        ]);

        $logRecord = $redactorProcessor($this->record([
            'password' => 'secret',
        ]));

        $this->assertSame('******', $logRecord->context['password']);
    }

    public function testCustomReplacementAffectsMasking(): void
    {
        $redactorProcessor = $this->createProcessor([
            'replacement' => '#',
            'rules'       => [
                'password' => new FullMaskRule(),
            ],
        ]);

        $logRecord = $redactorProcessor($this->record([
            'password' => 'secret',
        ]));

        $this->assertSame('######', $logRecord->context['password']);
    }

    public function testAppliesRuleToAllMatchingKeys(): void
    {
        $redactorProcessor = $this->createProcessor([
            'rules'             => [
                'password' => new FullMaskRule(),
            ],
            'use_default_rules' => false,
        ]);

        $logRecord = $redactorProcessor($this->record([
            'user'    => [
                'name'     => 'John',
                'password' => 'topsecret',
            ],
            'account' => [
                'full_name' => 'John Doe',
                'password'  => 'topsecret',
            ],
        ]));

        $this->assertSame('*********', $logRecord->context['user']['password']);
        $this->assertSame('*********', $logRecord->context['account']['password']);
        $this->assertSame('John', $logRecord->context['user']['name']);
    }

    public function testDisablesDefaultRulesWhenConfigured(): void
    {
        $record = $this->record([
            'email' => 'john.doe@example.com',
        ]);

        $redactorProcessor = $this->createProcessor([
            'use_default_rules' => false,
        ]);
        $processed = $redactorProcessor($record);

        $this->assertSame('john.doe@example.com', $processed->context['email']);

        $processorWithDefaults = $this->createProcessor([]);
        $logRecord             = $processorWithDefaults($record);

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

    public function testTemplateWrapsMaskedPart(): void
    {
        $redactorProcessor = $this->createProcessor([
            'use_default_rules' => false,
            'template'          => '[%s]',
            'rules'             => [
                'card' => new OffsetRule(4),
            ],
        ]);

        $logRecord = $redactorProcessor($this->record([
            'card' => '1234567890',
        ]));

        $this->assertSame('1234[******]', $logRecord->context['card']);
    }

    public function testOverflowPlaceholderMarksTruncatedContainer(): void
    {
        $redactorProcessor = $this->createProcessor([
            'use_default_rules'       => false,
            'max_items_per_container' => 1,
            'overflow_placeholder'    => 'MORE',
        ]);

        $logRecord = $redactorProcessor($this->record([
            'a' => 1,
            'b' => 2,
        ]));

        $this->assertSame([
            'a'                      => 1,
            '__redaction_overflow__' => 'MORE',
        ], $logRecord->context);
    }

    public function testNullOverflowPlaceholderDropsOverflowMarker(): void
    {
        $redactorProcessor = $this->createProcessor([
            'use_default_rules'       => false,
            'max_items_per_container' => 1,
            'overflow_placeholder'    => null,
        ]);

        $logRecord = $redactorProcessor($this->record([
            'a' => 1,
            'b' => 2,
        ]));

        $this->assertSame([
            'a' => 1,
        ], $logRecord->context);
    }

    public function testCopyObjectViewModeKeepsObjectCopy(): void
    {
        $redactorProcessor = $this->createProcessor([
            'use_default_rules' => false,
            'object_view_mode'  => ObjectViewModeEnum::Copy->value,
        ]);

        $logRecord = $redactorProcessor($this->record([
            'user' => (object) [
                'name' => 'John',
            ],
        ]));

        $this->assertEquals((object) [
            'name' => 'John',
        ], $logRecord->context['user']);
    }

    public function testPublicArrayObjectViewModeExposesOnlyPublicProperties(): void
    {
        $redactorProcessor = $this->createProcessor([
            'use_default_rules' => false,
            'object_view_mode'  => ObjectViewModeEnum::PublicArray,
        ]);

        $logRecord = $redactorProcessor($this->record([
            'user' => new class {
                public string $name      = 'John';
                private string $password = 'secret';

                public function password(): string
                {
                    return $this->password;
                }
            },
        ]));

        $this->assertSame([
            'name' => 'John',
        ], $logRecord->context['user']);
    }

    public function testInvalidObjectViewModeFailsWithResolverException(): void
    {
        $this->expectException(ConfigReaderException::class);
        $this->expectExceptionMessage('object_view_mode');

        $this->createProcessor([
            'object_view_mode' => 'unknown',
        ]);
    }

    public function testLimitExceededCallbackReceivesLimitDetails(): void
    {
        $calls             = [];
        $redactorProcessor = $this->createProcessor([
            'use_default_rules'          => false,
            'max_items_per_container'    => 1,
            'on_limit_exceeded_callback' => static function(array $info) use (&$calls): void {
                $calls[] = $info;
            },
        ]);

        $redactorProcessor($this->record([
            'a' => 1,
            'b' => 2,
        ]));

        $this->assertCount(1, $calls);
        $this->assertSame('maxItemsPerContainer', $calls[0]['type']);
        $this->assertSame('b', $calls[0]['key']);
    }

    public function testNonCallableLimitExceededCallbackFails(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('Redactor option "on_limit_exceeded_callback" must be callable or null.');

        $this->createProcessor([
            'on_limit_exceeded_callback' => 'not-a-function',
        ]);
    }

    public function testLengthLimitShortensMaskedValue(): void
    {
        $redactorProcessor = $this->createProcessor([
            'use_default_rules' => false,
            'length_limit'      => 3,
            'rules'             => [
                'password' => new FullMaskRule(),
            ],
        ]);

        $logRecord = $redactorProcessor($this->record([
            'password' => 'secretvalue',
        ]));

        $this->assertSame('***', $logRecord->context['password']);
    }

    public function testMaxDepthReplacesDeeperValuesWithPlaceholder(): void
    {
        $redactorProcessor = $this->createProcessor([
            'use_default_rules' => false,
            'max_depth'         => 1,
        ]);

        $logRecord = $redactorProcessor($this->record([
            'a' => [
                'b' => [
                    'c' => 1,
                ],
            ],
        ]));

        $this->assertSame([
            'a' => '...',
        ], $logRecord->context);
    }

    public function testMaxTotalNodesStopsTraversal(): void
    {
        $redactorProcessor = $this->createProcessor([
            'use_default_rules' => false,
            'max_total_nodes'   => 2,
        ]);

        $logRecord = $redactorProcessor($this->record([
            'a' => 1,
            'b' => 2,
            'c' => 3,
        ]));

        $this->assertSame([
            'a' => 1,
            'b' => 2,
            'c' => '...',
        ], $logRecord->context);
    }

    #[DataProvider('intOptionProvider')]
    public function testNonIntLimitOptionFails(string $option): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage("Redactor option '{$option}' must be an int or null.");

        $this->createProcessor([
            $option => '3',
        ]);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function intOptionProvider(): iterable
    {
        yield 'length_limit' => ['length_limit'];

        yield 'max_depth' => ['max_depth'];

        yield 'max_items_per_container' => ['max_items_per_container'];

        yield 'max_total_nodes' => ['max_total_nodes'];
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
