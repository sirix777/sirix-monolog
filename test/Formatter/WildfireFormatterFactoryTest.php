<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Formatter;

use Monolog\Formatter\WildfireFormatter;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Formatter\WildfireFormatterFactory;

class WildfireFormatterFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $options = ['dateFormat' => 'c'];
        $factory = new WildfireFormatterFactory();
        $formatter = $factory($options);

        $this->assertInstanceOf(WildfireFormatter::class, $formatter);
    }
}
