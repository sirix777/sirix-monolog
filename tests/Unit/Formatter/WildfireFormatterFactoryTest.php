<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Formatter;

use Sirix\Monolog\Formatter\WildfireFormatterFactory;
use Codeception\Test\Unit;
use Monolog\Formatter\WildfireFormatter;

class WildfireFormatterFactoryTest extends Unit
{
    public function testInvoke()
    {
        $options = ['dateFormat' => 'c'];
        $factory = new WildfireFormatterFactory();
        $formatter = $factory($options);

        $this->assertInstanceOf(WildfireFormatter::class, $formatter);
    }
}
