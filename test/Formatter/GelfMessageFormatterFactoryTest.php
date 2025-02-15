<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Formatter;

use Monolog\Formatter\GelfMessageFormatter;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Formatter\GelfMessageFormatterFactory;

class GelfMessageFormatterFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $options = [
            'systemName' => 'my-name',
            'extraPrefix' => 'extraPrefix_',
            'contextPrefix' => 'contextPrefix_',
        ];

        $factory = new GelfMessageFormatterFactory();
        $formatter = $factory($options);

        $this->assertInstanceOf(GelfMessageFormatter::class, $formatter);
    }
}
