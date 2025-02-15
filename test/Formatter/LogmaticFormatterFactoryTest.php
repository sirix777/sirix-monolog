<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Formatter;

use Monolog\Formatter\LogmaticFormatter;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Formatter\LogmaticFormatterFactory;

class LogmaticFormatterFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $options = [
            'batchMode' => LogmaticFormatter::BATCH_MODE_NEWLINES,
            'appendNewline' => false,
            'hostname' => 'my host',
            'appName' => 'my app',
        ];

        $factory = new LogmaticFormatterFactory();
        $formatter = $factory($options);

        $this->assertInstanceOf(LogmaticFormatter::class, $formatter);
    }
}
