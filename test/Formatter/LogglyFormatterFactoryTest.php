<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Formatter;

use Monolog\Formatter\LogglyFormatter;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Formatter\LogglyFormatterFactory;

class LogglyFormatterFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $options = [
            'batchMode' => LogglyFormatter::BATCH_MODE_NEWLINES,
            'appendNewline' => false,
        ];

        $factory = new LogglyFormatterFactory();
        $formatter = $factory($options);

        $this->assertInstanceOf(LogglyFormatter::class, $formatter);
    }
}
