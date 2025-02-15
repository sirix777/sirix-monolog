<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Formatter;

use Monolog\Formatter\NormalizerFormatter;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Formatter\NormalizerFormatterFactory;

class NormalizerFormatterFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $options = ['dateFormat' => 'c'];
        $factory = new NormalizerFormatterFactory();
        $formatter = $factory($options);

        $this->assertInstanceOf(NormalizerFormatter::class, $formatter);
    }
}
