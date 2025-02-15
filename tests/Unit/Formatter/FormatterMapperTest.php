<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit\Formatter;

use Sirix\Monolog\Formatter\ChromePHPFormatterFactory;
use Sirix\Monolog\Formatter\ElasticaFormatterFactory;
use Sirix\Monolog\Formatter\FlowdockFormatterFactory;
use Sirix\Monolog\Formatter\FormatterMapper;
use Sirix\Monolog\Formatter\GelfMessageFormatterFactory;
use Sirix\Monolog\Formatter\HtmlFormatterFactory;
use Sirix\Monolog\Formatter\JsonFormatterFactory;
use Sirix\Monolog\Formatter\LineFormatterFactory;
use Sirix\Monolog\Formatter\LogglyFormatterFactory;
use Sirix\Monolog\Formatter\LogmaticFormatterFactory;
use Sirix\Monolog\Formatter\LogstashFormatterFactory;
use Sirix\Monolog\Formatter\MongoDBFormatterFactory;
use Sirix\Monolog\Formatter\NormalizerFormatterFactory;
use Sirix\Monolog\Formatter\ScalarFormatterFactory;
use Sirix\Monolog\Formatter\WildfireFormatterFactory;
use Codeception\Test\Unit;

class FormatterMapperTest extends Unit
{
    private FormatterMapper $mapper;

    // @phpcs:ignore
    public function _before(): void
    {
        parent::_before();
        $this->mapper = new FormatterMapper();
    }

    public function testMapLine()
    {
        $expected = LineFormatterFactory::class;
        $result = $this->mapper->map('line');
        $this->assertEquals($expected, $result);
    }

    public function testMapHtml()
    {
        $expected = HtmlFormatterFactory::class;
        $result = $this->mapper->map('html');
        $this->assertEquals($expected, $result);
    }

    public function testMapNormalizer()
    {
        $expected = NormalizerFormatterFactory::class;
        $result = $this->mapper->map('normalizer');
        $this->assertEquals($expected, $result);
    }

    public function testMapScalar()
    {
        $expected = ScalarFormatterFactory::class;
        $result = $this->mapper->map('scalar');
        $this->assertEquals($expected, $result);
    }

    public function testMapJson()
    {
        $expected = JsonFormatterFactory::class;
        $result = $this->mapper->map('json');
        $this->assertEquals($expected, $result);
    }

    public function testMapWildfire()
    {
        $expected = WildfireFormatterFactory::class;
        $result = $this->mapper->map('wildfire');
        $this->assertEquals($expected, $result);
    }

    public function testMapChromePHP()
    {
        $expected = ChromePHPFormatterFactory::class;
        $result = $this->mapper->map('chromePHP');
        $this->assertEquals($expected, $result);
    }

    public function testMapGelf()
    {
        $expected = GelfMessageFormatterFactory::class;
        $result = $this->mapper->map('gelf');
        $this->assertEquals($expected, $result);
    }

    public function testMapLogStash()
    {
        $expected = LogstashFormatterFactory::class;
        $result = $this->mapper->map('logstash');
        $this->assertEquals($expected, $result);
    }

    public function testMapElastica()
    {
        $expected = ElasticaFormatterFactory::class;
        $result = $this->mapper->map('elastica');
        $this->assertEquals($expected, $result);
    }

    public function testMapLoggly()
    {
        $expected = LogglyFormatterFactory::class;
        $result = $this->mapper->map('loggly');
        $this->assertEquals($expected, $result);
    }

    public function testMapFlowdock()
    {
        $expected = FlowdockFormatterFactory::class;
        $result = $this->mapper->map('flowdock');
        $this->assertEquals($expected, $result);
    }

    public function testMapMongoDb()
    {
        $expected = MongoDBFormatterFactory::class;
        $result = $this->mapper->map('mongodb');
        $this->assertEquals($expected, $result);
    }

    public function testLogmatic()
    {
        $expected = LogmaticFormatterFactory::class;
        $result = $this->mapper->map('logmatic');
        $this->assertEquals($expected, $result);
    }

    public function testMapNotFound()
    {
        $result = $this->mapper->map('notHere');
        $this->assertNull($result);
    }
}
