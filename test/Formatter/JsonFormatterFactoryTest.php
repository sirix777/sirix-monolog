<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Formatter;

use Monolog\Formatter\JsonFormatter;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Formatter\JsonFormatterFactory;

class JsonFormatterFactoryTest extends TestCase
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
