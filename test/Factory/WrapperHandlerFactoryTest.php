<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Factory;

use Monolog\Level;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Sirix\Monolog\ConfigProvider;
use Sirix\Monolog\Enum\ConfigKey as C;
use Sirix\Monolog\Enum\HandlerType;
use Sirix\Monolog\Exception\InvalidConfigException;
use Sirix\Test\Monolog\Support\ArrayContainer;

use function fclose;
use function fopen;
use function rewind;
use function stream_get_contents;
use function sys_get_temp_dir;
use function tempnam;
use function unlink;

final class WrapperHandlerFactoryTest extends TestCase
{
    public function testGroupHandlerWritesToAllNestedHandlers(): void
    {
        $first = fopen('php://temp', 'w+');
        $second = fopen('php://temp', 'w+');
        $this->assertIsResource($first);
        $this->assertIsResource($second);

        $logger = $this->logger([
            'group' => [
                C::Type->value => HandlerType::Group,
                C::Options->value => [
                    C::Handlers->value => ['first', 'second'],
                ],
            ],
            'first' => $this->streamHandler($first),
            'second' => $this->streamHandler($second),
        ], ['group']);

        $logger->warning('Grouped message');

        $firstContents = $this->contents($first);
        $secondContents = $this->contents($second);
        fclose($first);
        fclose($second);

        $this->assertStringContainsString('Grouped message', $firstContents);
        $this->assertStringContainsString('Grouped message', $secondContents);
    }

    public function testFilterHandlerBlocksRecordsBelowConfiguredLevel(): void
    {
        $stream = fopen('php://temp', 'w+');
        $this->assertIsResource($stream);

        $logger = $this->logger([
            'filter' => [
                C::Type->value => HandlerType::Filter,
                C::Options->value => [
                    'handler' => 'stream',
                    'min_level_or_list' => Level::Error,
                ],
            ],
            'stream' => $this->streamHandler($stream),
        ], ['filter']);

        $logger->info('Info is filtered');
        $logger->error('Error is written');

        $contents = $this->contents($stream);
        fclose($stream);

        $this->assertStringNotContainsString('Info is filtered', $contents);
        $this->assertStringContainsString('Error is written', $contents);
    }

    public function testBufferHandlerFlushesNestedHandlerOnClose(): void
    {
        $stream = fopen('php://temp', 'w+');
        $this->assertIsResource($stream);

        $logger = $this->logger([
            'buffer' => [
                C::Type->value => HandlerType::Buffer,
                C::Options->value => [
                    'handler' => 'stream',
                ],
            ],
            'stream' => $this->streamHandler($stream),
        ], ['buffer']);

        $logger->info('Buffered message');
        $this->assertSame('', $this->contents($stream));

        $logger->close();
        $contents = $this->contents($stream);
        fclose($stream);

        $this->assertStringContainsString('Buffered message', $contents);
    }

    public function testFingersCrossedHandlerFlushesBufferWhenActivated(): void
    {
        $stream = fopen('php://temp', 'w+');
        $this->assertIsResource($stream);

        $logger = $this->logger([
            'fingers' => [
                C::Type->value => HandlerType::FingersCrossed,
                C::Options->value => [
                    'handler' => 'stream',
                    'activation_strategy' => Level::Warning,
                ],
            ],
            'stream' => $this->streamHandler($stream),
        ], ['fingers']);

        $logger->info('Buffered before warning');
        $this->assertSame('', $this->contents($stream));

        $logger->warning('Warning activates handler');
        $contents = $this->contents($stream);
        fclose($stream);

        $this->assertStringContainsString('Buffered before warning', $contents);
        $this->assertStringContainsString('Warning activates handler', $contents);
    }

    public function testWhatFailureGroupHandlerWritesToNestedHandlers(): void
    {
        $first = fopen('php://temp', 'w+');
        $second = fopen('php://temp', 'w+');
        $this->assertIsResource($first);
        $this->assertIsResource($second);

        $logger = $this->logger([
            'group' => [
                C::Type->value => HandlerType::WhatFailureGroup,
                C::Options->value => [
                    C::Handlers->value => ['first', 'second'],
                ],
            ],
            'first' => $this->streamHandler($first),
            'second' => $this->streamHandler($second),
        ], ['group']);

        $logger->warning('What failure message');

        $firstContents = $this->contents($first);
        $secondContents = $this->contents($second);
        fclose($first);
        fclose($second);

        $this->assertStringContainsString('What failure message', $firstContents);
        $this->assertStringContainsString('What failure message', $secondContents);
    }

    public function testFallbackGroupHandlerStopsAfterFirstSuccessfulHandler(): void
    {
        $first = fopen('php://temp', 'w+');
        $second = fopen('php://temp', 'w+');
        $this->assertIsResource($first);
        $this->assertIsResource($second);

        $logger = $this->logger([
            'fallback' => [
                C::Type->value => HandlerType::FallbackGroup,
                C::Options->value => [
                    C::Handlers->value => ['first', 'second'],
                ],
            ],
            'first' => $this->streamHandler($first),
            'second' => $this->streamHandler($second),
        ], ['fallback']);

        $logger->warning('Fallback message');

        $firstContents = $this->contents($first);
        $secondContents = $this->contents($second);
        fclose($first);
        fclose($second);

        $this->assertStringContainsString('Fallback message', $firstContents);
        $this->assertSame('', $secondContents);
    }

    public function testSamplingHandlerWithFactorOneWritesEveryRecord(): void
    {
        $stream = fopen('php://temp', 'w+');
        $this->assertIsResource($stream);

        $logger = $this->logger([
            'sampling' => [
                C::Type->value => HandlerType::Sampling,
                C::Options->value => [
                    'handler' => 'stream',
                    'factor' => 1,
                ],
            ],
            'stream' => $this->streamHandler($stream),
        ], ['sampling']);

        $logger->info('Sampled message');
        $contents = $this->contents($stream);
        fclose($stream);

        $this->assertStringContainsString('Sampled message', $contents);
    }

    public function testOverflowHandlerFlushesWhenThresholdIsReached(): void
    {
        $stream = fopen('php://temp', 'w+');
        $this->assertIsResource($stream);

        $logger = $this->logger([
            'overflow' => [
                C::Type->value => HandlerType::Overflow,
                C::Options->value => [
                    'handler' => 'stream',
                    'threshold_map' => [
                        'warning' => 1,
                    ],
                ],
            ],
            'stream' => $this->streamHandler($stream),
        ], ['overflow']);

        $logger->warning('Buffered warning');
        $this->assertSame('', $this->contents($stream));

        $logger->warning('Threshold warning');
        $contents = $this->contents($stream);
        fclose($stream);

        $this->assertStringContainsString('Buffered warning', $contents);
        $this->assertStringContainsString('Threshold warning', $contents);
    }

    public function testDeduplicationHandlerFlushesToNestedHandlerOnClose(): void
    {
        $stream = fopen('php://temp', 'w+');
        $store = tempnam(sys_get_temp_dir(), 'sirix-monolog-dedup-');
        $this->assertIsResource($stream);
        $this->assertIsString($store);

        $logger = $this->logger([
            'deduplication' => [
                C::Type->value => HandlerType::Deduplication,
                C::Options->value => [
                    'handler' => 'stream',
                    'deduplication_store' => $store,
                    'deduplication_level' => Level::Debug,
                    'time' => 60,
                ],
            ],
            'stream' => $this->streamHandler($stream),
        ], ['deduplication']);

        $logger->error('Deduplicated message');
        $this->assertSame('', $this->contents($stream));

        $logger->close();
        $contents = $this->contents($stream);
        fclose($stream);
        unlink($store);

        $this->assertStringContainsString('Deduplicated message', $contents);
    }

    public function testCircularHandlerReferenceFailsPredictably(): void
    {
        $container = ArrayContainer::fromConfigProvider([
            C::Root->value => [
                C::Channels->value => [
                    'default' => [
                        C::Handlers->value => ['loop'],
                    ],
                ],
                C::Handlers->value => [
                    'loop' => [
                        C::Type->value => HandlerType::Group,
                        C::Options->value => [
                            C::Handlers->value => ['loop'],
                        ],
                    ],
                ],
            ],
        ], (new ConfigProvider())());

        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage("Circular monolog handler reference detected for 'loop'.");

        $container->get(LoggerInterface::class);
    }

    /**
     * @param array<string, array<string, mixed>> $handlers
     * @param list<string>                        $channelHandlers
     */
    private function logger(array $handlers, array $channelHandlers): Logger
    {
        $container = ArrayContainer::fromConfigProvider([
            C::Root->value => [
                C::Channels->value => [
                    'default' => [
                        C::Name->value => 'app',
                        C::Handlers->value => $channelHandlers,
                    ],
                ],
                C::Handlers->value => $handlers,
            ],
        ], (new ConfigProvider())());

        $logger = $container->get(LoggerInterface::class);
        $this->assertInstanceOf(Logger::class, $logger);

        return $logger;
    }

    /**
     * @param resource $stream
     *
     * @return array<string, mixed>
     */
    private function streamHandler(mixed $stream): array
    {
        return [
            C::Type->value => HandlerType::Stream,
            C::Options->value => [
                'stream' => $stream,
            ],
        ];
    }

    /**
     * @param resource $stream
     */
    private function contents(mixed $stream): string
    {
        rewind($stream);
        $contents = stream_get_contents($stream);
        $this->assertIsString($contents);

        return $contents;
    }
}
