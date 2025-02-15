<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Formatter;

use Sirix\Monolog\Formatter\LineFormatterFactory;
use Codeception\Test\Unit;
use Monolog\Formatter\LineFormatter;

class LineFormatterFactoryTest extends Unit
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
