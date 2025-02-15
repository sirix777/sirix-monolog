<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Formatter;

use Sirix\Monolog\Formatter\LogstashFormatterFactory;
use Codeception\Test\Unit;
use Monolog\Formatter\LogstashFormatter;

class LogstashFormatterFactoryTest extends Unit
{
    public function testInvoke()
    {
        $options = [
            'applicationName' => "my-app",
            'systemName' => "my-name",
            'extraPrefix' => 'extraPrefix_',
            'contextPrefix' => 'contextPrefix_',
        ];

        $factory = new LogstashFormatterFactory();
        $formatter = $factory($options);

        $this->assertInstanceOf(LogstashFormatter::class, $formatter);
    }
}
