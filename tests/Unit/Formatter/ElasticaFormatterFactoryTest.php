<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Formatter;

use Sirix\Monolog\Formatter\ElasticaFormatterFactory;
use Codeception\Test\Unit;
use Monolog\Formatter\ElasticaFormatter;

class ElasticaFormatterFactoryTest extends Unit
{
    public function testInvoke()
    {
        $options = [
            'index' => "my-index",
            'type' => "doc-type",
        ];

        $factory = new ElasticaFormatterFactory();
        $formatter = $factory($options);

        $this->assertInstanceOf(ElasticaFormatter::class, $formatter);
    }
}
