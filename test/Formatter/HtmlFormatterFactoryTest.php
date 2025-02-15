<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Formatter;

use Monolog\Formatter\HtmlFormatter;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Formatter\HtmlFormatterFactory;

class HtmlFormatterFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $options = ['dateFormat' => 'c'];
        $factory = new HtmlFormatterFactory();
        $formatter = $factory($options);

        $this->assertInstanceOf(HtmlFormatter::class, $formatter);
    }
}
