<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Formatter;

use Sirix\Monolog\Formatter\GelfMessageFormatterFactory;
use Codeception\Test\Unit;
use Monolog\Formatter\GelfMessageFormatter;

class GelfMessageFormatterFactoryTest extends Unit
{
    public function testInvoke()
    {
        $options = [
            'systemName' => "my-name",
            'extraPrefix' => 'extraPrefix_',
            'contextPrefix' => 'contextPrefix_',
        ];

        $factory = new GelfMessageFormatterFactory();
        $formatter = $factory($options);

        $this->assertInstanceOf(GelfMessageFormatter::class, $formatter);
    }
}
