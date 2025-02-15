<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Formatter;

use Sirix\Monolog\Formatter\ScalarFormatterFactory;
use Codeception\Test\Unit;
use Monolog\Formatter\ScalarFormatter;

class ScalarFormatterFactoryTest extends Unit
{
    public function testInvoke()
    {
        $options = [];
        $factory = new ScalarFormatterFactory();
        $formatter = $factory($options);

        $this->assertInstanceOf(ScalarFormatter::class, $formatter);
    }
}
