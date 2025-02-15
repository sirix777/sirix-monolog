<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Formatter;

use Monolog\Formatter\ElasticaFormatter;
use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Formatter\ElasticaFormatterFactory;

class ElasticaFormatterFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $options = [
            'index' => 'my-index',
            'type' => 'doc-type',
        ];

        $factory = new ElasticaFormatterFactory();
        $formatter = $factory($options);

        $this->assertInstanceOf(ElasticaFormatter::class, $formatter);
    }
}
