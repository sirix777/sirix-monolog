<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Formatter;

use Sirix\Monolog\Formatter\LogmaticFormatterFactory;
use Codeception\Test\Unit;
use Monolog\Formatter\LogmaticFormatter;

class LogmaticFormatterFactoryTest extends Unit
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
