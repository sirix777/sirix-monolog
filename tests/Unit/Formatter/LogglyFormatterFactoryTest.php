<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Formatter;

use Sirix\Monolog\Formatter\LogglyFormatterFactory;
use Codeception\Test\Unit;
use Monolog\Formatter\LogglyFormatter;

class LogglyFormatterFactoryTest extends Unit
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
