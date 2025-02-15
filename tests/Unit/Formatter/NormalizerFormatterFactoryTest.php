<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Formatter;

use Sirix\Monolog\Formatter\NormalizerFormatterFactory;
use Codeception\Test\Unit;
use Monolog\Formatter\NormalizerFormatter;

class NormalizerFormatterFactoryTest extends Unit
{
    public function testInvoke()
    {
        $options = ['dateFormat' => 'c'];
        $factory = new NormalizerFormatterFactory();
        $formatter = $factory($options);

        $this->assertInstanceOf(NormalizerFormatter::class, $formatter);
    }
}
