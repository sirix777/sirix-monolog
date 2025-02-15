<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Formatter;

use Monolog\Formatter\ChromePHPFormatter;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Formatter\ChromePHPFormatterFactory;

class ChromePHPFormatterFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $options = [];
        $factory = new ChromePHPFormatterFactory();
        $formatter = $factory($options);

        $this->assertInstanceOf(ChromePHPFormatter::class, $formatter);
    }
}
