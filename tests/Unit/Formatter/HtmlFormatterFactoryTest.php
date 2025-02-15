<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Formatter;

use Sirix\Monolog\Formatter\HtmlFormatterFactory;
use Codeception\Test\Unit;
use Monolog\Formatter\HtmlFormatter;

class HtmlFormatterFactoryTest extends Unit
{
    public function testInvoke()
    {
        $options = ['dateFormat' => 'c'];
        $factory = new HtmlFormatterFactory();
        $formatter = $factory($options);

        $this->assertInstanceOf(HtmlFormatter::class, $formatter);
    }
}
