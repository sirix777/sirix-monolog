<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Formatter;

use Monolog\Formatter\MongoDBFormatter;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Formatter\MongoDBFormatterFactory;

class MongoDBFormatterFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $options = [
            'maxNestingLevel' => 'some-source',
            'exceptionTraceAsString' => true,
        ];

        $factory = new MongoDBFormatterFactory();
        $formatter = $factory($options);

        $this->assertInstanceOf(MongoDBFormatter::class, $formatter);
    }
}
