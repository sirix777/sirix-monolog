<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Formatter;

use Monolog\Formatter\ScalarFormatter;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Formatter\ScalarFormatterFactory;

class ScalarFormatterFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $options = [];
        $factory = new ScalarFormatterFactory();
        $formatter = $factory($options);

        $this->assertInstanceOf(ScalarFormatter::class, $formatter);
    }
}
