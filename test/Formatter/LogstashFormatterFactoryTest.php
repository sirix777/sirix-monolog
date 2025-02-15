<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Formatter;

use Monolog\Formatter\LogstashFormatter;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Formatter\LogstashFormatterFactory;

class LogstashFormatterFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $options = [
            'applicationName' => 'my-app',
            'systemName' => 'my-name',
            'extraPrefix' => 'extraPrefix_',
            'contextPrefix' => 'contextPrefix_',
        ];

        $factory = new LogstashFormatterFactory();
        $formatter = $factory($options);

        $this->assertInstanceOf(LogstashFormatter::class, $formatter);
    }
}
