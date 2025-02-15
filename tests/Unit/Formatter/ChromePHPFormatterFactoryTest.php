<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Formatter;

use Sirix\Monolog\Formatter\ChromePHPFormatterFactory;
use Codeception\Test\Unit;
use Monolog\Formatter\ChromePHPFormatter;

class ChromePHPFormatterFactoryTest extends Unit
{
    public function testInvoke()
    {
        $options = [];
        $factory = new ChromePHPFormatterFactory();
        $formatter = $factory($options);

        $this->assertInstanceOf(ChromePHPFormatter::class, $formatter);
    }
}
