<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Formatter;

use Sirix\Monolog\Formatter\FlowdockFormatterFactory;
use Codeception\Test\Unit;
use Monolog\Formatter\FlowdockFormatter;

class FlowdockFormatterFactoryTest extends Unit
{
    public function testInvoke()
    {
        $options = [
            'source' => 'some-source',
            'sourceEmail' => 'some-email',
        ];

        $factory = new FlowdockFormatterFactory();
        $formatter = $factory($options);

        $this->assertInstanceOf(FlowdockFormatter::class, $formatter);
    }
}
