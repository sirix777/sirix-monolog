<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Processor;

use DateTimeImmutable;
use Monolog\Level;
use Monolog\LogRecord;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Processor\PushoverDeviceProcessor;

final class PushoverDeviceProcessorTest extends TestCase
{
    public function testAddsDeviceToExtraWhenProvided(): void
    {
        $processor = new PushoverDeviceProcessor('ipad');

        $record = new LogRecord(
            datetime: new DateTimeImmutable('2025-10-10T00:00:00Z'),
            channel: 'test',
            level: Level::Info,
            message: 'hello',
            context: [],
            extra: []
        );

        $processed = $processor($record);

        $this->assertArrayHasKey('device', $processed->extra);
        $this->assertSame('ipad', $processed->extra['device']);
    }

    public function testDoesNotAddDeviceWhenNotProvided(): void
    {
        $processor = new PushoverDeviceProcessor();

        $record = new LogRecord(
            datetime: new DateTimeImmutable('2025-10-10T00:00:00Z'),
            channel: 'test',
            level: Level::Info,
            message: 'hello',
            context: [],
            extra: []
        );

        $processed = $processor($record);

        $this->assertArrayNotHasKey('device', $processed->extra);
    }
}
