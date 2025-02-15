<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Formatter;

use Monolog\Formatter\FlowdockFormatter;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Formatter\FlowdockFormatterFactory;

class FlowdockFormatterFactoryTest extends TestCase
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
