<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Formatter;

use Sirix\Monolog\Formatter\JsonFormatterFactory;
use Codeception\Test\Unit;
use Monolog\Formatter\JsonFormatter;

class JsonFormatterFactoryTest extends Unit
{
    public function testInvoke()
    {
        $options = [
            'batchMode' => JsonFormatter::BATCH_MODE_NEWLINES,
            'appendNewline' => false,
        ];

        $factory = new JsonFormatterFactory();
        $formatter = $factory($options);

        $this->assertInstanceOf(JsonFormatter::class, $formatter);
    }
}
