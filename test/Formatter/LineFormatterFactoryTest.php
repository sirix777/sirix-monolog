<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Formatter;

use Monolog\Formatter\LineFormatter;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Formatter\LineFormatterFactory;

class LineFormatterFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $options = [
            'format' => "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n",
            'dateFormat' => 'c',
            'allowInlineLineBreaks' => true,
            'ignoreEmptyContextAndExtra' => true,
        ];

        $factory = new LineFormatterFactory();
        $formatter = $factory($options);

        $this->assertInstanceOf(LineFormatter::class, $formatter);
    }
}
