<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Formatter;

use Sirix\Monolog\Formatter\MongoDBFormatterFactory;
use Codeception\Test\Unit;
use Monolog\Formatter\MongoDBFormatter;

class MongoDBFormatterFactoryTest extends Unit
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
